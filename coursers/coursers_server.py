from flask import Flask,request,jsonify
from njupt import Zhengfang
from njupt.exceptions import AuthenticationException
app = Flask(__name__)

@app.route("/zhengfang")
def zhengfang():
    print('123')
    account = request.args.get('account')
    password = request.args.get('password')
    try:
        zhengfang = Zhengfang(account=account,password=password)
        coursers = zhengfang.get_coursers()
    except AuthenticationException:
        return jsonify({'success': False, 'message':'账号或密码有误','coursers':[]})
    except Exception:
        return jsonify({'success': False, 'message':'未知异常','coursers':[]})

    for courser in coursers:
        if '单周' in courser['week'] or '双周' in courser['week']:
            courser['interval'] = 2
        else:
            courser['interval'] = 1
    return jsonify({'success':True,'coursers':coursers,'message':''})

if __name__ == "__main__":
    app.debug=True
    app.run()