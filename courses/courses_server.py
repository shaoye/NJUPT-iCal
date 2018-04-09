from flask import Flask,request,jsonify
from njupt import Zhengfang
from njupt.exceptions import AuthenticationException
app = Flask(__name__)

@app.route("/zhengfang")
def zhengfang():
    account = request.args.get('account')
    password = request.args.get('password')
    try:
        zhengfang = Zhengfang(account=account,password=password)
        courses = zhengfang.get_courses()
    except AuthenticationException:
        return jsonify({'success': False, 'message':'账号或密码有误','coursers':[]})
    except Exception:
        return jsonify({'success': False, 'message':'未知异常','coursers':[]})
    return jsonify({'success':True,'courses':courses,'message':''})

if __name__ == "__main__":
    app.debug=True
    app.run()