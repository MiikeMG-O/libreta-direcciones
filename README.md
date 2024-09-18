# Laravel 11 + Angular Project

This project uses Laravel 11 for the backend and Angular for the frontend. Angular is located in the `resources/front` folder of Laravel, and the compiled files are located in `public/front`.

## Requirements

- PHP >= 8.1
- Composer
- Node.js >= 20.x
- Angular CLI 14.2

### Default Ports

- **Laravel API**: Runs by default on `http://localhost:8000`.
- **Angular Frontend**: Runs by default on `http://localhost:4200`.

## Installation

1. **Clone the repository:**
   git clone <repository-url>
   cd <project-name>

2. **Install all the dependencies using composer**
   composer install

3. **Install Angular CLI (versión 18.0.1):**
   npm install -g @angular/cli@18.0.1

4. **Install angular dependencies**
   cd resources/front
   npm install

5. **Configure Laravel .env file:**
   cp .env.example .env

6. **Generate a new application key**
   php artisan key:generate

7. **install & Build Angular app**
   cd resources/front
   ng build --configuration=production

8. **Migrate and seed**
    php artisan migrate --seed

9 . **Start the local development server**
   php artisan serve

## Development

1 . **Start the local development server**
    php artisan serve

2 . **Serve Angular frontend:**
    cd resources/front
    ng serve

## FAQ

### How do I change the API base URL in Angular?

You can modify the API base URL in the environment files (`environment.ts` and `environment.prod.ts`) inside the Angular project:

```typescript
export const environment = {
  production: false,
  apiUrl: 'http://localhost:8000/api/'
};
