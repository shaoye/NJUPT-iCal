1. pipenv install --three
1. pipenv install --system  #如果在Linux下面报错, 使用这条命令.
2. pipenv shell
3. gunicorn -w4 -b0.0.0.0:8000 courses_server:app & # 推荐使用supervisor