FROM python:3.7-alpine

WORKDIR /api

ENV FLASK_APP=api.py

ENV FLASK_RUN_HOST=0.0.0.0

RUN apk add gcc libc-dev jpeg-dev zlib-dev libxml2 libxslt-dev

RUN pip install flask pytz njupt

EXPOSE 5000

COPY . .

CMD ["flask", "run"]