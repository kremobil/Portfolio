# Windows XP Portfolio

## Idea
This project will be an interactive portfolio designed to mimic the Windows XP desktop environment. My projects will be organized into folders on the desktop, with each project's description stored in a `.txt` file. Users will be able to view and edit these descriptions in Notepad application. The website aim to be fully responsive across all device resolutions.

## Local Setup
**Prerequisites:** PHP 8.x, Composer, Node.js.

### Clone and Install Dependencies:
```bash
git clone [repository-url]
composer install
npm install
```
### Environment Configuration:
Create a .env file from the example:
```bash
cp .env.example .env
```
Generate the application key:
```bash
php artisan key:generate
```
Configure your database settings in the .env file.
### Database Migration:
```bash
php artisan migrate
```
### Run the Application:
Start the Vite development server:
```bash
npm run dev
```
Start the Laravel server in a separate terminal:
```bash
Start the Laravel server in a separate terminal:
```
