<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NFC
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role = null): Response
    {
        // Проверяваме дали сесията 'nfc_scanned' е зададена и е истина (true)
        if (! $request->session()->has('nfc_scanned') || ! $request->session()->get('nfc_scanned')) {
            // Ако не е сканирано, пренасочваме обратно към страницата за сканиране
            return redirect()->route('scan');
        }

        // Ако е подадена роля, проверяваме дали ролята в сесията съвпада
        if ($role && $request->session()->get('nfc_role') !== $role) {
            // Ако ролята не съвпада, пренасочваме към страницата за сканиране или към някаква грешка
            return redirect()->route('scan')->withErrors('Не сте оторизирани за този достъп.');
        }

        return $next($request);
    }
}