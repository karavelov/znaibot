<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Начало', route('home'));
});

// Home > Blog
Breadcrumbs::for('blog', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Новини', route('blog'));
});

// Home > Blog > [Post]
Breadcrumbs::for('blog.details', function (BreadcrumbTrail $trail, $blog_post) {
    $trail->parent('blog');
    $trail->push($blog_post->title, route('blog.details', $blog_post));
});

// Home > promotions
Breadcrumbs::for('promotions', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Промоции', route('promotions'));
});

// Home > /pages/{slug}
Breadcrumbs::for('pages.show', function (BreadcrumbTrail $trail, $page) {
    $trail->parent('home');
    $trail->push($page->title, route('pages.show', $page));
});

// Home > Contacts
Breadcrumbs::for('contact', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Контакти', route('contact'));
});

// Home > Login
Breadcrumbs::for('login', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Вход / Регистрация', route('login'));
});

// Home > Products
Breadcrumbs::for('product-cat', function (BreadcrumbTrail $trail, $product) {
    $trail->push('Продукти', route('product.cat', $product));
});

// Home > Products > Product Details
Breadcrumbs::for('product.detail', function (BreadcrumbTrail $trail, $product) {
    $trail->parent('home');
    $trail->parent('product-cat', $product->category->slug);
    $trail->push($product->name, route('product.detail', $product));
});

// Home > [Wishlist]
Breadcrumbs::for('user.wishlist.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Любими продукти', route('user.wishlist.index'));
});

// Home > [Cart Details]
Breadcrumbs::for('cart-details', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Количка', route('cart-details'));
});

// Home > Cart Details > Checkout
Breadcrumbs::for('checkout', function (BreadcrumbTrail $trail) {
    $trail->parent('cart-details');
    $trail->push('Поръчка', route('checkout'));
});

// Home > Checkout > [Payment]
Breadcrumbs::for('payment', function (BreadcrumbTrail $trail) {
    $trail->parent('checkout');
    $trail->push('Плащане', route('payment'));
});