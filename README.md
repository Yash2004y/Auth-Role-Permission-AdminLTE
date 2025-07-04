<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions">
    <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
  </a>
</p>

# Auth Role Permission

This is a Laravel-based application that implements a full-featured authentication system with user **roles** and **permissions** management.

It is ideal for applications that need access control and user-level security with fine-grained permission handling.

---

## Features

- User registration & authentication
- Role-based access control
- Permission management
- Dynamic permission assignment to roles
- Assign roles to users
- Middleware protection based on roles/permissions
- Laravel Blade support with `@can` and custom directives
- Admin panel (optional) to manage users/roles/permissions

---

## Technologies Used

- Laravel Framework (v10+)
- Laravel Breeze / Jetstream / Fortify (depending on your stack)
- Spatie Laravel Permission Package
- MySQL or any Laravel-supported DB
- Bootstrap or Tailwind CSS (optional UI stack)

---

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/auth-role-permission.git
   cd auth-role-permission
