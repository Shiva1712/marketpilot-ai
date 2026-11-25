php -v
composer -V
python --version
node -v
npm -v
mysql --version
git --version


composer create-project laravel/laravel:^12.0 backend

cd backend

php artisan key:generate
php artisan serve --port=8000


*****************************************************
cd ai-service

python -m venv venv

venv\Scripts\activate

python -m pip install --upgrade pip



****************************************************
Terminal 1 – Laravel
cd marketpilot-ai/backend
php artisan serve --port=8000

http://127.0.0.1:8000


Terminal 2 – Python

cd marketpilot-ai/ai-service
venv\Scripts\activate
uvicorn app.main:app --reload --port=8001

http://127.0.0.1:8001/docs

Terminal 3 – React

cd marketpilot-ai/frontend
npm run dev

http://localhost:5173


Later: Terminal 4 – Redis
docker run -d --name marketpilot-redis -p 6379:6379 redis:7