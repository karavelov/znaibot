<!-- Клавиатура ZnaiBot -->
<div id="znk-keyboard" class="znk-keyboard">
    <div class="znk-toolbar flex items-center justify-between px-6 py-2 bg-white/90 backdrop-blur-md border-b border-gray-100 rounded-t-[2rem]">
        <div id="znk-layouts" class="flex gap-2"></div>
        <div class="flex items-center gap-4">
            <span class="text-[10px] text-blue-600 uppercase tracking-[0.2em] font-black">ЗнайБот</span>
            <button type="button" onclick="hideKeyboard()" class="w-8 h-8 flex items-center justify-center bg-gray-100 text-gray-500 rounded-full hover:bg-gray-200 transition-all">
                <i class="fas fa-chevron-down text-xs"></i>
            </button>
        </div>
    </div>
    <div id="znk-keys-container" class="p-2 bg-[#F2F2F7] flex flex-col gap-1.5 shadow-inner"></div>
</div>

<style>
    .znk-keyboard {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        z-index: 99999;
        background: #F2F2F7;
        display: none;
        flex-direction: column;
        box-shadow: 0 -15px 50px rgba(0,0,0,0.15);
        border-top: 1px solid #D1D1D6;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translateY(100%);
    }

    .znk-keyboard.visible {
        display: flex;
        transform: translateY(0);
    }

    .znk-keyboard.mini-mode {
        width: 60%;
        left: 20%;
        right: 20%;
        border-radius: 2rem 2rem 0 0;
    }

    .znk-row {
        display: flex;
        justify-content: center;
        width: 100%;
        gap: 4px;
    }

    .znk-key {
        flex: 1;
        height: 54px;
        max-width: 68px;
        background: #FFFFFF;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        font-weight: 600;
        color: #1D1D1F;
        box-shadow: 0 1.5px 0 #C7C7CC;
        cursor: pointer;
        user-select: none;
        touch-action: manipulation;
    }

    .znk-key:active {
        background: #D1D1D6;
        transform: scale(0.94);
        box-shadow: 0 0 0 transparent;
    }

    .znk-key.special {
        background: #AEB3BE;
        color: #1D1D1F;
        font-size: 1rem;
    }

    .znk-key.action-blue {
        background: #2563EB;
        color: #FFFFFF !important;
        box-shadow: 0 2px 0 #1D4ED8;
        max-width: none;
        flex: 2.5;
        font-size: 1rem;
    }

    .znk-key.space-bar {
        flex: 5;
        max-width: none;
        font-size: 0.9rem;
        color: #8E8E93;
    }

    .znk-layout-btn {
        padding: 5px 12px;
        font-size: 0.7rem;
        font-weight: 800;
        border-radius: 8px;
        background: #E5E5EA;
        color: #8E8E93;
        transition: all 0.2s;
    }

    .znk-layout-btn.active {
        background: #2563EB;
        color: #FFFFFF;
    }
</style>

<script>
    (function() {
        const container = document.getElementById('znk-keys-container');
        const layoutsEl = document.getElementById('znk-layouts');
        const keyboardEl = document.getElementById('znk-keyboard');
        
        let activeTarget = null;
        let currentLayout = 'bg_phonetic';
        let lastLanguage = 'bg_phonetic'; // Помни дали сме били на БГ или ЕН преди символите
        let shiftEnabled = false;

        const layouts = {
            bg_phonetic: {
                label: 'BG',
                rows: [
                    ['я','в','е','р','т','ъ','у','и','о','п','ш','щ'],
                    ['а','с','д','ф','г','х','й','к','л','ч','ж'],
                    ['{shift}','з','ь','ц','б','н','м','ю','{bksp}'],
                    ['{123}','{globe}','{space}','{enter}']
                ]
            },
            en_qwerty: {
                label: 'EN',
                rows: [
                    ['q','w','e','r','t','y','u','i','o','p'],
                    ['a','s','d','f','g','h','j','k','l'],
                    ['{shift}','z','x','c','v','b','n','m','{bksp}'],
                    ['{123}','{globe}','{space}','{enter}']
                ]
            },
            symbols: {
                label: '123',
                rows: [
                    ['1','2','3','4','5','6','7','8','9','0'],
                    ['-','/',':',';','(',')','$','&','@','"'],
                    ['.',',','?','!','_','\\','|','№','{bksp}'],
                    ['{abc}','{space}','{enter}']
                ]
            }
        };

        function renderKeyboard() {
            container.innerHTML = '';
            
            const layout = layouts[currentLayout];

            layout.rows.forEach(row => {
                const rowDiv = document.createElement('div');
                rowDiv.className = 'znk-row';
                row.forEach(key => {
                    const btn = document.createElement('div');
                    btn.className = 'znk-key';
                    let label = key;

                    if(key === '{shift}') { 
                        btn.classList.add('special'); 
                        label = '<i class="fas fa-arrow-up"></i>';
                        if(shiftEnabled) { btn.style.background = "#2563EB"; btn.style.color = "#FFFFFF"; }
                    }
                    else if(key === '{bksp}') { 
                        btn.classList.add('special'); 
                        label = '<i class="fas fa-backspace"></i>'; 
                    }
                    else if(key === '{space}') { 
                        btn.classList.add('space-bar'); 
                        label = 'интервал'; 
                    }
                    else if(key === '{enter}') { 
                        btn.classList.add('action-blue'); 
                        label = 'изпрати'; 
                    }
                    else if(key === '{123}') {
                        btn.classList.add('special');
                        label = '123';
                    }
                    else if(key === '{abc}') {
                        btn.classList.add('special');
                        label = 'ABC';
                    }
                    else if(key === '{globe}') {
                        btn.classList.add('special');
                        label = '<i class="fas fa-globe"></i>';
                    }
                    else { 
                        if(shiftEnabled) label = key.toUpperCase(); 
                    }

                    btn.innerHTML = label;
                    btn.onpointerdown = (e) => { e.preventDefault(); handleKeyAction(key); };
                    rowDiv.appendChild(btn);
                });
                container.appendChild(rowDiv);
            });
        }

        function handleKeyAction(key) {
            if (!activeTarget) return;
            if (activeTarget.disabled || activeTarget.readOnly) return;
            
            if (key === '{shift}') {
                shiftEnabled = !shiftEnabled;
                renderKeyboard();
            } else if (key === '{bksp}') {
                const start = activeTarget.selectionStart;
                const end = activeTarget.selectionEnd;
                const val = activeTarget.value;
                if (start !== end) {
                    activeTarget.value = val.slice(0, start) + val.slice(end);
                    activeTarget.setSelectionRange(start, start);
                } else if (start > 0) {
                    activeTarget.value = val.slice(0, start - 1) + val.slice(end);
                    activeTarget.setSelectionRange(start - 1, start - 1);
                }
            } else if (key === '{space}') {
                insertText(' ');
            } else if (key === '{enter}') {
                const submitBtn = activeTarget.form?.querySelector('button[type="submit"]');
                if (!submitBtn || !submitBtn.disabled) {
                    activeTarget.form?.requestSubmit();
                }
            } else if (key === '{123}') {
                currentLayout = 'symbols';
                renderKeyboard();
            } else if (key === '{abc}') {
                currentLayout = lastLanguage;
                renderKeyboard();
            } else if (key === '{globe}') {
                lastLanguage = lastLanguage === 'bg_phonetic' ? 'en_qwerty' : 'bg_phonetic';
                currentLayout = lastLanguage;
                renderKeyboard();
            } else {
                insertText(shiftEnabled ? key.toUpperCase() : key);
                if(shiftEnabled) { shiftEnabled = false; renderKeyboard(); }
            }
            activeTarget.dispatchEvent(new Event('input', { bubbles: true }));
        }

        function insertText(text) {
            const start = activeTarget.selectionStart;
            const end = activeTarget.selectionEnd;
            const val = activeTarget.value;
            activeTarget.value = val.slice(0, start) + text + val.slice(end);
            activeTarget.setSelectionRange(start + text.length, start + text.length);
        }

        window.showKeyboard = function() {
            if (activeTarget && activeTarget.classList.contains('kb-mini')) {
                keyboardEl.classList.add('mini-mode');
            } else {
                keyboardEl.classList.remove('mini-mode');
            }
            
            keyboardEl.classList.add('visible');
            document.body.style.paddingBottom = keyboardEl.classList.contains('mini-mode') ? "280px" : "360px";

            setTimeout(() => {
                activeTarget.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
        }

        window.hideKeyboard = function() {
            keyboardEl.classList.remove('visible');
            document.body.style.paddingBottom = "0px";
        }

        document.addEventListener('focusin', (e) => {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                activeTarget = e.target;
                showKeyboard();
            }
        });

        renderKeyboard();
    })();
</script>