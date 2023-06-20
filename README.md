# StudBud

[![License](https://img.shields.io/packagist/l/laravel/framework)](https://packagist.org/packages/laravel/framework)

StudBud is a web platform designed for students, teachers, and universities to create their web pages and exchange ideas and help with other users. What sets StudBud apart from other social networking platforms is its exclusive focus on these purposes. StudBud requires users to provide mandatory information such as their educational institution, specialization, and if they are a student, or their workplace and the educational institution they are affiliated with if they are a teacher. This allows for efficient filtering and finding people who are relevant to your interests, enabling you to seek assistance or engage in meaningful conversations.

The platform features a built-in chat messenger and a powerful people filtering system to fulfill its intended purpose. Additionally, users can exchange files and documents, and each user has a dedicated page where they can upload various files to be shared with others, which is a unique feature not commonly found in other social networking platforms. Alongside these features, StudBud provides a post sharing system similar to that of Facebook. The user registration process is designed to be user-friendly for all types of users.

The project is built using Vue.js, HTML5, and CSS for the frontend. The backend utilizes SQL as the database management language and Laravel as the technology for handling database operations from the frontend.

## Installation
To run StudBud locally, follow these steps:

1. Clone the repository: `git clone https://github.com/augustin4k/LaravelStudbud.git`
2. Install dependencies: `npm install`
3. Configure the database connection in the `.env` file
4. Run the migrations: `php artisan migrate`
5. Start the development server: `npm run serve`
6. Access the application at `http://localhost:8080`

## License
StudBud is open-source software licensed under the [MIT license](LICENSE).
