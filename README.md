# Laravel Blog API

## Overview

This is a RESTful API for a blog application built with Laravel. It supports Posts, Categories, Tags, Users, and Comments with features such as filtering posts by author, tags, and categories, user authentication, and notifications.

---

## Features

- CRUD operations on Posts, Comments, Categories, and Tags
- Posts can have multiple Tags and belong to Categories (including nested parent categories)
- Users can register, login, and manage their own posts and comments
- Posts automatically get a "new" tag on creation, and an "edited" tag if updated the title or content.
- Comments trigger email notifications to the post author
- API filtering by author, tags, and categories
- Authentication via Laravel Sanctum API tokens
- Uses Laravel Eloquent ORM with migrations and seeders

---

## Prerequisites

- PHP >= 8.0
- Composer
- MySQL or any supported database
- Mail log for email notifications

---