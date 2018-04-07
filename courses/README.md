1. pipenv install --three
2. pipenv shell
2. gunicorn -w4 -b0.0.0.0:8000 courses_server:app & # 推荐使用supervisor