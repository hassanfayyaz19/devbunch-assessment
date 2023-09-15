# Configuration

**Run these Following Commands to setup the project**

1. composer install
2. cp .env.example .env
3. php artisan generate:key
4. npm install
5. npm run dev
6. php artisan migrate --seed

**What i'm implemented:**

1. User Types Model + migration + seeder
2. User Seeder
3. Course CRUD using Jquery Ajax + Datatable Server Side
4. Created middleware AuthenticateTeacherOrAdmin and AuthenticateUserType

_Note: By Reading the assessment description, I'm not sure is it means to implement Multi Auth Guard or Just Simple Multi Auth.
But I'm implemented without any additional Guards._
