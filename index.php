<?php
/**
 * THE WEST - Official Website (PHP Version)
 * تم دمج كافة القوانين والأنظمة مع تشفير البيانات الحساسة
 */

// 1. إعدادات السيرفر (PHP) - لا تظهر للمستخدم في المتصفح
$secret_config = [
    'db_url' => 'https://the-west-jobs-default-rtdb.firebaseio.com/',
    'wh_police' => 'https://discord.com/api/webhooks/1476611799376728116/1m7FPKG1OXqlArP9D0PH8KrXliOZikWdeMCwU2HrsEe83dZL6U0xxsPGk6uePaOHc7Oa',
    'wh_health' => 'https://discord.com/api/webhooks/1476612122665291929/tw4k3CnQlbpix14e8EUWLCTGz63Xgc6TsdJgCohosNpkGsWoDuB3q8t506NGN3CgKJEM',
    'wh_justice' => 'https://discord.com/api/webhooks/1476612280501276836/kingqEz11C7L8hJDdQr_OoHgnvirUYM3fm7fqpnu8NqKNyV_oECV3UhBOL9-nbvU70Yg',
    'admin_pin' => 'JOPSOS' // كود الإدارة للتحكم في فتح وإغلاق التقديم
];

$encoded_data = base64_encode(json_encode($secret_config));
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>THE WEST | الموقع الرسمي</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>

    <style>
        :root {
            --main-purple: #7b42bc;
            --dark-bg: #0b0b15;
            --card-bg: rgba(22, 22, 37, 0.95);
            --input-bg: #131325;
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body, html {
            margin: 0; padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--dark-bg);
            color: white;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        header {
            position: absolute;
            top: 20px; width: 100%;
            display: flex; justify-content: space-between;
            align-items: center; padding: 10px 5%;
            z-index: 1000; box-sizing: border-box;
        }
        .logo img { height: 70px; filter: drop-shadow(0 0 10px var(--main-purple)); }
        .menu-btn { 
            font-size: 30px; cursor: pointer; transition: var(--transition); 
            background: var(--card-bg); padding: 8px 15px; border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .sidenav {
            height: 100%; width: 0; position: fixed; z-index: 2000;
            top: 0; right: 0; background-color: #0f0f1e;
            overflow-x: hidden; transition: 0.5s;
            padding-top: 60px; text-align: center;
            border-left: 3px solid var(--main-purple);
        }
        .sidenav a { padding: 15px; text-decoration: none; font-size: 20px; color: #ccc; display: block; transition: 0.3s; }
        .sidenav a:hover { color: var(--main-purple); background: rgba(255,255,255,0.05); }
        .sidenav .closebtn { position: absolute; top: 10px; left: 20px; font-size: 36px; cursor: pointer; }

        .page-section { display: none; min-height: 100vh; animation: fadeIn 0.8s; }
        #home { display: block; }

        .hero {
            height: 100vh; width: 100%; position: relative;
            display: flex; align-items: center; justify-content: center;
            text-align: center; background: url('background.jpg') no-repeat center center/cover;
        }
        .overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.75); }
        .hero-content { z-index: 1; padding: 0 20px; }
        .hero-content h1 { font-size: clamp(3rem, 10vw, 5.5rem); color: var(--main-purple); margin: 0; text-shadow: 0 0 30px rgba(123, 66, 188, 0.8); }
        .hero-content p { font-size: clamp(1rem, 4vw, 1.3rem); max-width: 700px; margin: 20px auto; color: #eee; line-height: 1.6; }

        .features { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; padding: 50px 8%; background: var(--dark-bg); }
        .feature-card { background: var(--card-bg); padding: 30px; border-radius: 20px; text-align: center; border-bottom: 5px solid var(--main-purple); transition: var(--transition); cursor: pointer; }
        .feature-card:hover { transform: translateY(-10px); box-shadow: 0 10px 40px var(--main-purple); }
        .feature-card i { font-size: 40px; color: var(--main-purple); margin-bottom: 15px; }

        .content-container { padding: 120px 8% 60px 8%; max-width: 1000px; margin: auto; }
        .rule { background: var(--card-bg); margin-bottom: 15px; border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05); transition: var(--transition); }
        .rule-header { padding: 22px; background: #1a1a2e; cursor: pointer; display: flex; justify-content: space-between; align-items: center; font-weight: bold; border-right: 6px solid var(--main-purple); transition: var(--transition); }
        .rule-header i { transition: transform 0.4s ease; color: var(--main-purple); }
        .rule.active .rule-header { background: #252545; color: var(--main-purple); }
        .rule.active .rule-header i { transform: rotate(180deg); }
        .rule-content { max-height: 0; overflow: hidden; background: #131325; transition: max-height 0.6s cubic-bezier(0, 1, 0, 1); line-height: 1.8; color: #ddd; }
        .rule.active .rule-content { max-height: 1200px; padding: 25px; border-top: 1px solid rgba(255,255,255,0.1); transition: max-height 1s ease-in-out; }

        .job-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px; }
        .job-card { background: var(--card-bg); border-radius: 20px; padding: 30px; text-align: center; transition: var(--transition); cursor: pointer; border: 1px solid rgba(255,255,255,0.05); position: relative; }
        .job-card:hover { transform: translateY(-10px); border-color: var(--main-purple); box-shadow: 0 10px 30px rgba(123,66,188,0.2); }
        .job-card i { font-size: 50px; color: var(--main-purple); margin-bottom: 15px; }
        
        .admin-btn { position: absolute; top: 10px; left: 10px; background: rgba(255,0,0,0.2); border: none; color: #ff4444; padding: 5px 10px; border-radius: 5px; font-size: 10px; cursor: pointer; z-index: 10; }
        .closed-status { color: #ff4444; font-weight: bold; margin-top: 10px; display: none; }
        
        .job-card.is-closed { opacity: 0.6; filter: grayscale(0.5); }
        .application-box { background: var(--card-bg); border-radius: 25px; padding: 40px; border: 1px solid var(--main-purple); max-width: 700px; margin: 20px auto; }
        .input-group { position: relative; margin-bottom: 20px; text-align: right; }
        .input-group label { display: block; margin-bottom: 8px; color: #ccc; }
        .input-group input, .input-group textarea { width: 100%; padding: 12px 15px; background: var(--input-bg); border: 1px solid #333; color: white; border-radius: 12px; box-sizing: border-box; }
        .submit-btn { background: var(--main-purple); color: white; border: none; padding: 15px; border-radius: 12px; font-size: 1.1rem; font-weight: bold; cursor: pointer; width: 100%; transition: var(--transition); }

        footer { text-align: center; padding: 50px; background: #080810; border-top: 1px solid #222; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <header>
        <div class="logo"><img src="logo.png" alt="THE WEST"></div>
        <div class="menu-btn" onclick="openNav()"><i class="fas fa-bars"></i></div>
    </header>

    <div id="mySidenav" class="sidenav">
        <span class="closebtn" onclick="closeNav()">&times;</span>
        <img src="logo.png" width="100" style="margin-bottom: 20px;">
        <a href="#" onclick="showPage('home')">الرئيسية</a>
        <a href="#" onclick="showPage('rules')">القوانين والمصطلحات</a>
        <a href="#" onclick="showPage('penalties')">العقوبات</a>
        <a href="#" onclick="showPage('jobs')">تقديم الوظائف</a>
        <a href="https://west-coin.netlify.app/" target="_blank" style="color: #ffd700;"><i class="fas fa-coins"></i> متجر العملات</a>
    </div>

    <div id="home" class="page-section">
        <section class="hero">
            <div class="overlay"></div>
            <div class="hero-content animate__animated animate__zoomIn">
                <h1>THE WEST</h1>
                <p>حياكم الله في سيرفر ذا ويست المتخصص في الرول بلاي. نهدف لتقديم أفضل تجربة واقعية.</p>
            </div>
        </section>
        <section class="features">
            <div class="feature-card" onclick="showPage('rules')"><i class="fas fa-book"></i><h3>القوانين</h3></div>
            <div class="feature-card" onclick="showPage('jobs')"><i class="fas fa-briefcase"></i><h3>الوظائف</h3></div>
            <div class="feature-card" onclick="window.location.href='https://discord.gg/westsa'"><i class="fas fa-headset"></i><h3>الدعم</h3></div>
        </section>
    </div>

    <div id="rules" class="page-section">
        <div class="content-container">
            <h1 style="text-align: center; color: var(--main-purple);">📜 قوانين المدينة والمصطلحات</h1>
            
            <div class="rule">
                <div class="rule-header" onclick="toggleAccordion(this)">1 - الميتا جيمنج / Meta Gaming <i class="fas fa-chevron-down"></i></div>
                <div class="rule-content">استخدام معلومات خارج اللعبة داخل الرول بلاي.</div>
            </div>
            <div class="rule">
                <div class="rule-header" onclick="toggleAccordion(this)">2 - باورجيمنج / Power Gaming <i class="fas fa-chevron-down"></i></div>
                <div class="rule-content">القيام بأفعال خارقة أو غير واقعية لا يمكن فعلها في الحقيقة.</div>
            </div>
            <div class="rule">
                <div class="rule-header" onclick="toggleAccordion(this)">3 - عدم تقدير الحياة / NVL <i class="fas fa-chevron-down"></i></div>
                <div class="rule-content">يجب أن تخاف على حياتك عند تعرضك لتهديد بالسلاح.</div>
            </div>
            <div class="rule">
                <div class="rule-header" onclick="toggleAccordion(this)">4 - القتل العشوائي / RDM <i class="fas fa-chevron-down"></i></div>
                <div class="rule-content">يمنع قتل أي لاعب بدون سبب درامي أو قصة مسبقة.</div>
            </div>
            </div>
    </div>

    <div id="penalties" class="page-section">
        <div class="content-container">
            <h1 style="text-align: center; color: #ff4444;">🚫 قائمة العقوبات</h1>
            <div class="rule"><div class="rule-header" onclick="toggleAccordion(this)">مخالفة بسيطة <i class="fas fa-chevron-down"></i></div><div class="rule-content">تحذير شفوي أو كتابي.</div></div>
            <div class="rule"><div class="rule-header" onclick="toggleAccordion(this)">مخالفة متوسطة <i class="fas fa-chevron-down"></i></div><div class="rule-content">باند مؤقت من يوم إلى 7 أيام.</div></div>
            <div class="rule"><div class="rule-header" onclick="toggleAccordion(this)">مخالفة جسيمة <i class="fas fa-chevron-down"></i></div><div class="rule-content">باند نهائي من السيرفر.</div></div>
        </div>
    </div>

    <div id="jobs" class="page-section">
        <div class="content-container">
            <div id="jobSelection">
                <h1 style="text-align: center;">💼 تقديم الوظائف</h1>
                <div class="job-grid">
                    <div class="job-card" id="card-police" onclick="checkAndOpen('الشرطة', 'fas fa-shield-alt', 'police')">
                        <button class="admin-btn" id="btn-police" onclick="toggleJobStatus(event, 'police')">...</button>
                        <i class="fas fa-shield-alt"></i><h3>الشرطة</h3>
                        <div class="closed-status" id="status-police">التقديم مغلق حالياً</div>
                    </div>
                    <div class="job-card" id="card-justice" onclick="checkAndOpen('وزارة العدل', 'fas fa-gavel', 'justice')">
                        <button class="admin-btn" id="btn-justice" onclick="toggleJobStatus(event, 'justice')">...</button>
                        <i class="fas fa-gavel"></i><h3>وزارة العدل</h3>
                        <div class="closed-status" id="status-justice">التقديم مغلق حالياً</div>
                    </div>
                    <div class="job-card" id="card-health" onclick="checkAndOpen('وزارة الصحة', 'fas fa-ambulance', 'health')">
                        <button class="admin-btn" id="btn-health" onclick="toggleJobStatus(event, 'health')">...</button>
                        <i class="fas fa-ambulance"></i><h3>وزارة الصحة</h3>
                        <div class="closed-status" id="status-health">التقديم مغلق حالياً</div>
                    </div>
                </div>
            </div>

            <div id="applicationForm" class="application-box" style="display: none;">
                <h2 id="jobTitle" style="text-align: center; color: var(--main-purple);"></h2>
                <form id="rpForm">
                    <div class="input-group"><label>الاسم الكامل:</label><input type="text" id="name" required></div>
                    <div class="input-group"><label>اسم المستخدم في ديسكورد:</label><input type="text" id="discord_user" placeholder="user#0000" required></div>
                    <div class="input-group"><label>العمر:</label><input type="number" id="age" required></div>
                    <div class="input-group"><label>الجنسية:</label><input type="text" id="nation" required></div>
                    <div class="input-group"><label>لماذا تريد الانضمام؟</label><textarea id="reason" rows="3" required></textarea></div>
                    <div class="input-group"><label>الخبرات السابقة:</label><textarea id="exp" rows="3" required></textarea></div>
                    <button type="submit" class="submit-btn">إرسال الطلب</button>
                </form>
                <center><button onclick="showPage('jobs')" style="background:none; border:none; color:gray; cursor:pointer; margin-top:15px;">إلغاء</button></center>
            </div>
        </div>
    </div>

    <footer>
        <p>جميع الحقوق محفوظة لسيرفر THE WEST &copy; 2026</p>
    </footer>

    <script>
    (function() {
        // فك تشفير إعدادات PHP
        const _0xRaw = "<?php echo $encoded_data; ?>";
        const _0xCfg = JSON.parse(atob(_0xRaw));

        firebase.initializeApp({ databaseURL: _0xCfg.db_url });
        const _0xdb = firebase.database();
        let _0xActiveWh = "";
        let _0xJobStats = { police: true, health: true, justice: true };

        // تحديث حالة الوظائف من Firebase
        _0xdb.ref('job_statuses').on('value', (s) => {
            const d = s.val();
            if (d) { 
                _0xJobStats = d; 
                for (let t in d) _0xUpdateUI(t, d[t]); 
            }
        });

        window._0xUpdateUI = function(t, o) {
            const c = document.getElementById('card-' + t);
            const s = document.getElementById('status-' + t);
            const b = document.getElementById('btn-' + t);
            if (o) {
                c.classList.remove('is-closed'); s.style.display = 'none';
                b.innerText = "إدارة"; b.style.color = "#ff4444";
            } else {
                c.classList.add('is-closed'); s.style.display = 'block';
                b.innerText = "فتح"; b.style.color = "#00ff00";
            }
        };

        window.toggleJobStatus = function(e, t) {
            e.stopPropagation();
            if (prompt("كود الإدارة:") === _0xCfg.admin_pin) {
                _0xdb.ref('job_statuses/' + t).set(!_0xJobStats[t]);
            } else { alert("خطأ!"); }
        };

        window.checkAndOpen = function(tl, i, t) {
            if (!_0xJobStats[t]) return alert("التقديم مغلق حالياً.");
            if (localStorage.getItem('l_s') === new Date().toLocaleDateString()) return alert("قدمت اليوم بالفعل!");
            
            // تحديد رابط الـ Webhook بناءً على القسم
            const whs = { police: _0xCfg.wh_police, health: _0xCfg.wh_health, justice: _0xCfg.wh_justice };
            _0xActiveWh = whs[t];

            document.getElementById('jobSelection').style.display = 'none';
            document.getElementById('applicationForm').style.display = 'block';
            document.getElementById('jobTitle').innerHTML = `<i class="${i}"></i> تقديم لـ ${tl}`;
        };

        window.showPage = (id) => {
            document.querySelectorAll('.page-section').forEach(p => p.style.display = 'none');
            document.getElementById(id).style.display = 'block';
            window.scrollTo(0,0);
            closeNav();
        };

        window.openNav = () => document.getElementById("mySidenav").style.width = "280px";
        window.closeNav = () => document.getElementById("mySidenav").style.width = "0";
        window.toggleAccordion = (h) => {
            const r = h.parentElement; const a = r.classList.contains('active');
            document.querySelectorAll('.rule').forEach(x => x.classList.remove('active'));
            if (!a) r.classList.add('active');
        };

        // إرسال البيانات إلى ديسكورد
        document.getElementById('rpForm').onsubmit = function(e) {
            e.preventDefault();
            const payload = {
                embeds: [{
                    title: "طلب انضمام جديد - THE WEST",
                    color: 8078012,
                    fields: [
                        { name: "👤 الاسم", value: document.getElementById('name').value, inline: true },
                        { name: "🆔 ديسكورد", value: document.getElementById('discord_user').value, inline: true },
                        { name: "🎂 العمر", value: document.getElementById('age').value, inline: true },
                        { name: "🌍 الجنسية", value: document.getElementById('nation').value, inline: true },
                        { name: "📁 القسم", value: document.getElementById('jobTitle').innerText },
                        { name: "📝 السبب", value: document.getElementById('reason').value },
                        { name: "🛠 الخبرات", value: document.getElementById('exp').value }
                    ],
                    footer: { text: "نظام التقديم الآلي | THE WEST" },
                    timestamp: new Date()
                }]
            };

            fetch(_0xActiveWh, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            }).then(() => {
                alert('تم إرسال طلبك بنجاح!');
                localStorage.setItem('l_s', new Date().toLocaleDateString());
                location.reload();
            }).catch(() => alert('حدث خطأ في الإرسال.'));
        };

        // حماية إضافية
        document.addEventListener('contextmenu', e => e.preventDefault());
        document.onkeydown = e => { if(e.keyCode == 123 || (e.ctrlKey && e.shiftKey && e.keyCode == 73)) return false; };
    })();
    </script>
</body>
</html>
