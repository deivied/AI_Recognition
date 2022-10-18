from flask import Flask, render_template, request, session, url_for, flash, Response
from flask_mysqldb import MySQL
from werkzeug.utils import redirect
import datetime


app = Flask(__name__)
app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'root'
app.config['MYSQL_PASSWORD'] = ''
app.config['MYSQL_DB'] = 'employees'
app.secret_key = 'sess_please'



mysql = MySQL(app)


@app.route('/reconnaissance')
def reconnaissance():
    return render_template('recognition.html')


@app.route('/video_feed')
def video_feed():
    return Response()


@app.route('/')
def index():
    date = datetime.datetime.now()
    h = date.hour
    m = date.minute
    s = date.second
    return render_template("index.html", heure=h, minute=m, seconde=s)


@app.route('/emplye')
def employe():
    if "telephone" in session:
        prenom = session['prenom']
        nom = session['nom']
        tel = session['telephone']
        return render_template("employe.html", prenom=prenom, nom=nom, tel=tel)
    else:
        redirect(url_for('login'))


@app.route('/login')
def login():
    return render_template("login.html")


@app.route('/supp')
def supp():
    return render_template("del.html")


@app.route('/register')
def register():
        prenom = session['prenom']
        return render_template("register.html", prenom=prenom)




@app.route('/admin')
def admin():
    if "telephone" in session:
        prenom = session['prenom']
        nom = session['nom']
        tel = session['telephone']
        return render_template("admin.html", prenom=prenom, nom=nom, tel=tel)
    elif "telephone" not in session:
        redirect(url_for('login'))
    else:
        return redirect(url_for('login'))


@app.route('/logform', methods=["GET", "POST"])
def logform():
    if request.method == "POST":
        tel = request.form['login']
        pwd = request.form['mdp']
        profil = request.form['profil']
        # pwds = hashlib.sha256(str(pwd).encode("utf-8")).hexdigest()
        cursor = mysql.connection.cursor()
        req_connection_client = "SELECT * FROM employe where telephone = '%s' AND mdp = '%s' AND profil = '%s' "
        cursor.execute(req_connection_client % (tel, pwd, profil))
        resultat_connection_client = cursor.fetchall()
        cursor.close()
        if len(resultat_connection_client) > 0:
            if profil == "admin":
                session["prenom"] = resultat_connection_client[0][1]
                session["nom"] = resultat_connection_client[0][2]
                session["telephone"] = tel
                return redirect(url_for('admin'))
            else:
                session["prenom"] = resultat_connection_client[0][1]
                session["nom"] = resultat_connection_client[0][2]
                session["telephone"] = tel
                return redirect(url_for('employe'))
        else:
            session['telephone'] = None
            error = "Cette login ou ce mot de passe ne sont pas valides, veuillez reessayer"
            return render_template("login.html", error=error)
    else:
        return redirect(url_for('login'))




@app.route('/delform', methods=['POST'])
def delform():
    return render_template("admin.html")


@app.route('/forregister', methods=['POST', 'GET'])
def forregister():
    if request.method == "POST":
        result = request.form
        p = result['prenom']
        n = result['nom']
        tel = result['tel']
        e = result['email']
        photo = result['tof']
        prof = result['profil']
        pwd = result['pass']
        pwdb = result['pass2']
        for champ in result:
            if len(champ) < 2:
                error = "Veuiller remplir tous les champs avec coherences"
                return render_template("register.html", error=error)

        if pwd == pwdb:
            cursor = mysql.connection.cursor()
            req_client_exist = "SELECT * FROM employe where telephone = '%s' "
            cursor.execute(req_client_exist % tel)
            result_client_exist = cursor.fetchall()
            cursor.close()
            if len(result_client_exist) > 0:
                error = "Un compte avec ce numero existe déjà, veuillez saisir un nouveau numero"
                return render_template("register.html", error=error)
            else:
                etat = "actif"
                cursor = mysql.connection.cursor()
                req_register_client = "INSERT INTO `employe` (prenom, nom, telephone, email, profil, mdp) VALUES (%s, %s, %s, %s, %s, %s)"
                cursor.execute(req_register_client % (p, n, tel, e, prof, pwd))
                cursor.close()
                msg = "Compte cree"
                return redirect(url_for('admin'))
        else:
            error = "Les deux mots de passe ne sont pas correct"
            return render_template("register.html", error=error)


@app.route('/deconnect', methods=['POST', 'GET'])
def deconnect():
    if request.method == 'POST':
        session.pop("telephone", None)
        flash('Vous etes maintenant deconnecte')
        return redirect(url_for('login'))
    else:
        redirect(url_for('admin'))
    

if __name__ == '__main__':
    app.run(debug=True)
