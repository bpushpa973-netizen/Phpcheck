<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIMT Journal of Management, Science & Technology | Harlal Institute, Greater Noida</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #0f172a; line-height: 1.5; }
        
        .top-bar {
            background: #0c2e3b;
            color: #e2e8f0;
            padding: 0.7rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            font-size: 0.8rem;
        }
        .journal-meta span { margin-right: 1.2rem; }
        .journal-meta i { margin-right: 0.3rem; color: #f59e0b; }
        .login-status {
            background: #1e4a6b;
            padding: 0.3rem 1rem;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 500;
            transition: 0.2s;
        }
        .login-status:hover { background: #2c6e9e; }
        
        nav {
            background: white;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid #eef2f6;
        }
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            padding: 0.8rem 2rem;
        }
        .nav-links {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            list-style: none;
        }
        .nav-links li {
            position: relative;
        }
        .nav-links li a {
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            color: #1e3a4b;
            padding: 0.6rem 1.2rem;
            border-radius: 40px;
            transition: 0.2s;
            display: block;
            cursor: pointer;
        }
        .nav-links li a:hover, .nav-links li a.active {
            background: #c2410c;
            color: white;
        }
        
        .dropdown {
            position: absolute;
            top: 45px;
            left: 0;
            background: white;
            min-width: 240px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            opacity: 0;
            visibility: hidden;
            transition: 0.2s ease;
            z-index: 200;
            border: 1px solid #eef2f6;
        }
        .nav-links li:hover .dropdown {
            opacity: 1;
            visibility: visible;
        }
        .dropdown a {
            display: block;
            padding: 0.7rem 1.2rem;
            color: #1e3a4b;
            font-size: 0.85rem;
            font-weight: 500;
            border-radius: 0;
            background: transparent;
            cursor: pointer;
        }
        .dropdown a:first-child { border-radius: 16px 16px 0 0; }
        .dropdown a:last-child { border-radius: 0 0 16px 16px; }
        .dropdown a:hover {
            background: #f1f5f9;
            color: #c2410c;
        }
        
        .hero {
            background: linear-gradient(135deg, #eef2f5 0%, #e2e8f0 100%);
            padding: 2.5rem 2rem;
            text-align: center;
            border-bottom: 1px solid #d1d9e6;
        }
        .hero h2 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 0.8rem;
        }
        .hero p {
            max-width: 800px;
            margin: 0 auto;
            font-size: 1rem;
            opacity: 0.9;
        }
        .btn-primary {
            background: #f59e0b;
            color: #0c2e3b;
            border: none;
            padding: 0.7rem 2rem;
            border-radius: 40px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            transition: 0.2s;
        }
        .btn-primary:hover { background: #ffb347; transform: translateY(-2px); }
        
        .main-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }
        .section-title {
            font-size: 1.4rem;
            font-weight: 700;
            border-left: 5px solid #c2410c;
            padding-left: 1rem;
            margin-bottom: 1.2rem;
        }
        .card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e9edf2;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .article-item {
            background: white;
            border-radius: 18px;
            padding: 1.2rem;
            margin-bottom: 1.2rem;
            border: 1px solid #eef2f6;
            transition: 0.2s;
        }
        .article-item:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .article-title { font-weight: 800; font-size: 1.05rem; color: #0f2b3d; }
        .article-authors { font-size: 0.8rem; color: #475569; margin: 0.3rem 0 0.6rem; }
        .article-meta { font-size: 0.7rem; color: #f59e0b; margin-bottom: 0.5rem; }
        .pdf-btn {
            background: #1e4a6b;
            color: white;
            border: none;
            padding: 0.35rem 1rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: 0.2s;
        }
        .pdf-btn:hover { background: #0f2b3d; }
        
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            justify-content: center;
            align-items: center;
            z-index: 1001;
        }
        .modal-content {
            background: white;
            width: 90%;
            max-width: 460px;
            border-radius: 32px;
            padding: 1.8rem;
            position: relative;
        }
        .close-modal {
            position: absolute;
            right: 1.2rem;
            top: 1rem;
            font-size: 1.6rem;
            cursor: pointer;
        }
        .form-tabs {
            display: flex;
            gap: 1rem;
            border-bottom: 1px solid #ddd;
            margin-bottom: 1.2rem;
        }
        .tab-btn {
            background: none;
            border: none;
            font-weight: 700;
            padding: 0.5rem 0;
            cursor: pointer;
            color: #5b6e8c;
        }
        .tab-btn.active {
            color: #c2410c;
            border-bottom: 2px solid #c2410c;
        }
        .form-group input {
            width: 100%;
            padding: 0.7rem;
            border-radius: 14px;
            border: 1px solid #cbd5e1;
            margin-bottom: 0.8rem;
        }
        .submit-btn {
            background: #c2410c;
            color: white;
            width: 100%;
            padding: 0.7rem;
            border-radius: 40px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .error-msg { color: #b91c1c; font-size: 0.7rem; text-align: center; margin-top: 0.3rem; }
        .success-msg { color: #15803d; font-size: 0.7rem; text-align: center; }
        footer {
            background: #0c2e3b;
            color: #cbd5e1;
            text-align: center;
            padding: 1.6rem;
            margin-top: 2rem;
            font-size: 0.8rem;
        }
        @media (max-width: 860px) {
            .main-container { grid-template-columns: 1fr; }
            .nav-container { overflow-x: auto; }
            .nav-links { flex-wrap: nowrap; }
        }
        .journal-stats {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            margin: 1rem 0;
            flex-wrap: wrap;
        }
        .stat-box {
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 0.5rem 1rem;
            text-align: center;
        }
        .stat-number { font-size: 1.3rem; font-weight: 800; }
        .stat-label { font-size: 0.7rem; opacity: 0.8; }
        .call-for-papers {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 0.8rem;
            margin: 1rem 0;
            border-radius: 12px;
        }
        .call-for-papers a { color: #c2410c; font-weight: 600; }
    </style>
</head>
<body>

<div class="top-bar">
    <div class="login-status" id="loginStatusBtn">
        <i class="fas fa-user-circle"></i> <span id="authStatusText">Login / Register</span>
    </div>
</div>

<nav>
    <div class="nav-container">
        <ul class="nav-links">
            <li><a href="#" data-page="home" class="active">Home</a></li>
            <li>
                <a href="#" id="aboutMainBtn">About <i class="fas fa-chevron-down"></i></a>
                <div class="dropdown" id="aboutDropdown">
                    <a href="#" data-page="about" data-subpage="aims">Aims & Scope</a>
                    <a href="#" data-page="about" data-subpage="institute">About the Journal</a>
                    <a href="#" data-page="about" data-subpage="indexing">Abstracting & Indexing</a>
                </div>
            </li>
            <li><a href="#" data-page="editorialAdvisory">Editorial Advisory Board</a></li>
            <li><a href="#" data-page="editorialBoard">Editorial Board</a></li>
            <li>
                <a href="#" id="issuesMainBtn">Table of Contents <i class="fas fa-chevron-down"></i></a>
                <div class="dropdown" id="issuesDropdown">
                    <a href="#" data-page="issues" data-subpage="current">Current Issue</a>
                    <a href="#" data-page="issues" data-subpage="allissues">All Issues</a>
                    <a href="#" data-page="issues" data-subpage="onlinefirst">Online First</a>
                </div>
            </li>
            <li><a href="#" data-page="guidelines">Submission Guidelines</a></li>
            <li><a href="#" data-page="contact">Contact</a></li>
        </ul>
    </div>
</nav>

<div class="hero" style="background: url('himt.webp') top center / cover no-repeat; width: 100%; height: 100vh; display: flex; align-items: flex-end; justify-content: center; padding-bottom: 3rem; margin: 0; border-bottom: none; position: relative; top: 0;">
    <button class="btn-primary" id="callToActionBtn" style="background: #f59e0b; color: #0c2e3b; font-weight: bold; font-size: 1.2rem; padding: 0.8rem 2.5rem; border-radius: 50px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); border: none; cursor: pointer; z-index: 10;"><i class="fas fa-upload"></i> Submit Paper / Access Journal</button>
</div>

<div class="main-container" id="dynamicContent"></div>

<footer>
    <p>Harlal Institute of Management And Technology, Greater Noida (U.P.) | HIMT Journal of Management, Science & Technology</p>
    <p>Harlal Institute Of Management And Technology, Knowledge Park I, Greater Noida, Uttar Pradesh - 201310</p>
</footer>

<div id="authModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="form-tabs">
            <button class="tab-btn active" data-tab="login">Login</button>
            <button class="tab-btn" data-tab="register">Register</button>
        </div>
        <div id="loginFormContainer">
            <form id="loginForm">
                <div class="form-group"><input type="email" id="loginEmail" placeholder="Email" required></div>
                <div class="form-group"><input type="password" id="loginPassword" placeholder="Password" required></div>
                <button type="submit" class="submit-btn">Login</button>
                <div id="loginError" class="error-msg"></div>
            </form>
        </div>
        <div id="registerFormContainer" style="display:none;">
            <form id="registerForm">
                <div class="form-group"><input type="text" id="regName" placeholder="Full Name" required></div>
                <div class="form-group"><input type="email" id="regEmail" placeholder="Email" required></div>
                <div class="form-group"><input type="tel" id="regPhone" placeholder="Mobile Number (10 digits)" required></div>
                <div class="form-group"><input type="text" id="regInstitute" placeholder="Institute / College Name" required></div>
                <div class="form-group"><input type="password" id="regPassword" placeholder="Password (min 6 chars)" required></div>
                <button type="submit" class="submit-btn">Register</button>
                <div id="regError" class="error-msg"></div>
                <div id="regSuccess" class="success-msg"></div>
            </form>
        </div>
    </div>
</div>

<script>
let currentUser = null;

async function apiRequest(action, formData = {}) {
    const response = await fetch('config.php?action=' + action, {
        method: 'POST',
        body: new URLSearchParams(formData),
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    });
    return await response.json();
}

async function checkSession() {
    try {
        const data = await apiRequest('check');
        if (data.loggedIn) {
            currentUser = data.user;
            document.getElementById('authStatusText').innerText = `Welcome ${data.user.name}`;
            document.getElementById('loginStatusBtn').innerHTML = `<i class="fas fa-user-check"></i> ${data.user.name} <i class="fas fa-sign-out-alt" style="margin-left:8px" id="logoutBtn"></i>`;
            const logoutBtn = document.getElementById('logoutBtn');
            if(logoutBtn) logoutBtn.onclick = () => logout();
        } else {
            currentUser = null;
            document.getElementById('authStatusText').innerText = "Login / Register";
            document.getElementById('loginStatusBtn').innerHTML = `<i class="fas fa-user-circle"></i> <span id="authStatusText">Login / Register</span>`;
            document.getElementById('loginStatusBtn').onclick = () => openModal();
        }
    } catch(e) { console.log(e); }
}

async function logout() {
    await apiRequest('logout');
    currentUser = null;
    checkSession();
    alert("Logged out successfully");
    location.reload();
}

function openModal() { document.getElementById('authModal').style.display = 'flex'; }
function closeModal() { document.getElementById('authModal').style.display = 'none'; }

function handlePdfDownload(paperId, title) {
    alert(`✅ Downloading PDF for "${title}".`);
}

// Articles data from IIM Shillong journal
const articlesData = [
    { title: "", authors: "", pages: "", date: "" },
    { title: "", authors: "", pages: "", date: "" },
    { title: "", authors: "", pages: "", date: "" },
    { title: "", authors: "", pages: "", date: "" },
    { title: "", authors: "", pages: "", date: "" }
];

function renderHome() {
    let articlesHtml = '';
    articlesData.forEach((art, idx) => {
        articlesHtml += `<div class="article-item">
                    <div class="article-title">${art.title}</div>
                    <div class="article-authors">${art.authors}</div>
                    <div class="article-meta"><i class=""></i> </div>
                    <button class="pdf-btn" onclick="handlePdfDownload(${idx}, '${art.title.replace(/'/g, "\\'")}')"><i class="fas fa-download"></i> Download PDF</button>
                 </div>`;
    });
    return `<div>
            <!-- Editor-in-Chief Message -->
            <div style="background: #f1f5f9; padding: 1.5rem; border-radius: 20px; margin-bottom: 1.5rem;">
                <h2 style="color: #0c2e3b;">Editor-in-Chief(Founder): Prof. (Dr) Sudhir Kumar</h2>
                <p><strong>Group Director, HIMT Group of Institutions, Greater Noida</strong></p>
                <p>It is with great pride and enthusiasm that I present the inaugural issue (Volume 1, Issue 1) of the HIMT Journal of Management, Science and Technology (HJMST). The launch of this bi-annual journal marks a significant academic milestone in our ongoing commitment to research excellence and knowledge dissemination.</p>
                <p>HJMST has been envisioned as a dynamic platform for scholars, researchers, academicians, and industry professionals to share original research, innovative ideas, and interdisciplinary perspectives in the fields of Management, Science, and Technology. In an era driven by rapid transformation and global challenges, the role of research in shaping sustainable and practical solutions has become more crucial than ever.</p>
                <p>This inaugural issue reflects our dedication to maintaining high standards of scholarly rigor, ethical publication practices, and a robust peer-review process. Each manuscript included in this volume has undergone a thorough review to ensure quality, relevance, and academic integrity.</p>
                <p>I extend my sincere gratitude to the authors for their valuable contributions, the reviewers for their insightful evaluations, and the Editorial Board for their unwavering support and commitment. Their collective efforts have made this first issue possible.</p>
                <p>We aspire for HJMST to evolve into a reputed national and international journal, fostering intellectual dialogue and contributing meaningfully to academic and professional communities.</p>
                <p>I warmly invite researchers and scholars to contribute to future issues and be a part of this growing academic endeavor.</p>
                <p><em>With best wishes</em></p>
            </div>

            <!-- Managing Editor Message -->
            <div style="background: #fef3c7; padding: 1.5rem; border-radius: 20px; margin-bottom: 1.5rem;">
                <h2 style="color: #0c2e3b;">Managing Editor(Founder): Prof. (Dr) Pankaj Kumar</h2>
                <p><strong>Director, Harlal Institute of Management and Technology, Greater Noida</strong></p>
                <p>It is a matter of great satisfaction to present the inaugural issue (Volume 1, Issue 1) of the HIMT Journal of Management, Science and Technology (HJMST). This bi-annual journal represents a significant step forward in strengthening the research culture and academic engagement at HIMT.</p>
                <p>HJMST has been conceptualized as a scholarly platform dedicated to publishing high-quality research in the fields of Management, Science, and Technology. Our objective is to encourage original thinking, promote interdisciplinary research, and provide an avenue for academicians, researchers, and industry professionals to disseminate their work to a wider audience.</p>
                <p>The successful release of this first issue has been possible due to the collective efforts of the Editorial Board, reviewers, authors, and the dedicated academic team who worked diligently at every stage from manuscript submission and peer review to editing and final publication. We are committed to ensuring transparency, timely publication, and adherence to ethical standards in all our processes.</p>
                <p>As we begin this academic journey, we look forward to receiving valuable research contributions in the forthcoming issues and to continuously enhancing the quality and reach of the journal.</p>
                <p>I extend my sincere thanks to everyone associated with this initiative and convey my best wishes for the continued growth and success of HJMST.</p>
            </div>

            <div class="call-for-papers">
                <i class="fas fa-file-alt"></i> <strong>Call for Papers:</strong> Volume 4, Issue 2 (Jul-Dec 2026). Last date: May 30, 2026. <a href="#" onclick="loadPage('guidelines'); return false;">Click here for details →</a>
            </div>
            <div class="section-title">Latest Research Articles</div>
            ${articlesHtml}
            </div>
            <div class="sidebar">
                <div class="card"><h3 style="margin-bottom:0.8rem;">About HIMT Journal</h3><p>HIMT Journal of Management, Science & Technology is the scholarly open access journal of Harlal Institute Of Management And Technology, Greater Noida. It publishes research contributions in all areas of business management and allied disciplines since 2022. Follows double-blind peer review and publishes two issues per year.</p></div>
                <div class="card"><h3>Abstracting & Indexing</h3><ul style="list-style:none"><li><i class="fas fa-check-circle" style="color:#c2410c"></i> Scopus (Emerging)</li><li><i class="fas fa-check-circle" style="color:#c2410c"></i> Web of Science (ESCI)</li><li><i class="fas fa-check-circle" style="color:#c2410c"></i> Google Scholar</li><li><i class="fas fa-check-circle" style="color:#c2410c"></i> DOAJ</li><li><i class="fas fa-check-circle" style="color:#c2410c"></i> Crossref</li></ul></div>
                <div class="card"><h3>Current Status</h3><p>Online & Print on Demand<br>Publishing Frequency: Bi-annual (July-Dec)<br><strong>Editor-in-Chief:</strong>Prof. (Dr) Sudhir Kumar, HIMT</p></div>
            </div>`;
}

function renderAimsScope() {
    return `
    <div class="card">
        
        <!-- Aims Section -->
        <div class="section-title">Aims</div>

        <p>The <strong>HIMT Journal of Management, Science and Technology (HJMST)</strong> aims to provide a dynamic and credible platform for academicians, researchers, industry professionals, and scholars to publish highquality research that contributes to the advancement of knowledge in Management, Science, and Technology.
        </p>

        <p style="margin-top:1rem;">
            <strong>The journal seeks to:</strong>
        </p>

        <p style="margin-top:1rem;">
            • Promote interdisciplinary research integrating Management practices with emerging Science and Technology trends.
        </p>

        <p style="margin-top:1rem;">
            • Encourage innovative, empirical, and applied research that addresses contemporary academic and industry challenges.
        </p>

        <p style="margin-top:1rem;">
            • Foster research culture among faculty members and students of HIMT and other institutions.
        </p>

        <p style="margin-top:1rem;">
            • Support knowledge dissemination that contributes to sustainable development and societal progress.
        </p>

        <p style="margin-top:1rem;">
            • Strengthen academia-industry collaboration through research-based insights and case studies.
        </p>

    </div>

    <!-- Scope Section -->
    <div class="card" style="margin-top:20px;">

        <div class="section-title">Scope</div>

        <p>
            HJMST welcomes original research papers, review articles, case studies,
            and conceptual papers in areas including, but not limited to:
        </p>

        <div style="
            display:flex;
            flex-wrap:wrap;
            gap:30px;
            margin-top:20px;
        ">

            <!-- Left Column -->
            <div style="flex:1; min-width:250px;">

                <h3 style="margin-bottom:10px;">
                    Management & Commerce
                </h3>

                <p>• Marketing Management</p>
                <p>• Human Resource Management</p>
                <p>• Financial Management</p>
                <p>• Operations & Supply Chain Management</p>
                <p>• International Business</p>
                <p>• Entrepreneurship & Start-ups</p>
                <p>• Strategic Management</p>
                <p>• Business Analytics</p>
                
                <h3 style=margin-top:30px; margin-bottom:10px;">
                    Science & Applied Science
                </h3>
                
                <p>• Biotechnology & Life Sciences</p>
                <p>• Environmental Science & Sustainability</p>
                <p>• Applied Sciences and Research Innovations</p>
                <p>• Health & Industrial Applications</p>

            </div>

            <!-- Right Column -->
            <div style="flex:1; min-width:250px;">

                <h3 style="margin-bottom:10px;">
                    Computer Applications & Technology
                </h3>

                <p>• Artificial Intelligence & Machine Learning</p>
                <p>• Data Science & Analytics</p>
                <p>• Cyber Security</p>
                <p>• Cloud Computing</p>
                <p>• Internet of Things (IoT)</p>
                <p>• Software Engineering</p>
                <p>• Blockchain Technology</p>
                <p>• Emerging Digital Technologies</p>
                
                <h3 style=margin-top:30px; margin-bottom:10px;">
                    Education & Social Sciences
                </h3>
                
                <p>• Educational Management</p>
                <p>• Teaching–Learning Innovations</p>
                <p>• Skill Development & Employability</p>
                <p>• Digital Education & E-Learning</p>
                <p>• Policy Studies & Governance</p>

            </div>
            The journal encourages interdisciplinary and collaborative research that integrates multiple domains to provide innovative solutions to real-world problems. 

        </div>

    </div>
    `;
}

function renderInstitute() {
    return `<div class="card"><div class="section-title">About The Journal</div>
            <p>HIMT Journal of Management, Science and Technology (HJMST) is the multidisciplinary academic journal of Harlal Institute of Management & Technology, a constituent Institution of HIMT Group of Institutions, Greater Noida. The journal serves as a scholarly platform to promote research, innovation, and knowledge dissemination in the fields of Management, Computer Applications, Biotechnology, Education, and allied disciplines.</p>
            <p style="margin-top:0.8rem;"><strong>Address:</strong> Knowledge Park I, Greater Noida, Uttar Pradesh - 201310</p>
            <p><strong>Editor-in-Chief:</strong> Dr. Sudhir Kumar, Director, HIMT Greater Noida</p>
            </div>`;
}

function renderIndexing() {
    return `<div class="card"><div class="section-title">Abstracting & Indexing</div>
            <p>The HIMT Journal of Management, Science & Technology is indexed/abstracted in:</p>
            <ul style="margin-left:1.2rem; margin-top:0.8rem;">
                <li>Index Copernicus International</li>
                <li>Google Scholar</li>
                <li>EBSCO</li>
                <li>NAAS Rating</li>
                <li>I2OR</li>
                <li>Cite Factor</li>
                <li>Indian Citation Index</li>
                <li>J-GATE</li>
                <li>Scientific Journal Impact Factor</li>
                <li>ISRA-JIF</li>
                <li>ESJI</li>
            </ul>
            </div>`;
}

function renderEditorialAdvisory() {
    return `<div class="card"><div class="section-title">Editorial Advisory Board</div>
            <p><strong>Editor-in-Chief:</strong> <strong>PROF. (Dr) SUDHIR KUMAR</strong>, Group Director, HIMT Group of Institutions, Greater Noida</p>
            <p><strong>Managing Editor:</strong> <strong>PROF. (Dr) PANKAJ KUMAR</strong>, Director, Harlal Institute of Management & Technology, Greater Noida</p>
            <p><strong>Members:</strong></p>
            <ul style="margin-left:1.2rem; margin-top:0.5rem;">
                <li><strong>PROF. (DR.) R. S. NIRJAR</strong> - Former Chairman, AICTE, and Former Vice-Chancellor, Gautam Buddha University, Greater Noida</li>
                <li><strong>PROF. (DR.) P.K. JAIN</strong> - Director General, Institute of Infrastructure, Technology, Research and Management (IITRAM), Ahmedabad</li>
                <li><strong>PROF. (DR.) PRADEEP KUMAR</strong> - Former Vice Chancellor, Delhi Technological University (DTU)</li>
                <li><strong>PROF. (DR.) SACHIN MAHESHWARI</strong> - Vice Chancellor, Guru Jambheshwar University, Moradabad</li>
                <li><strong>PROF. (DR.) M.P. POONIA</strong> - Former Vice Chairman, All India Council for Technical Education (AICTE)</li>
                <li><strong>PROF. (DR.) BRAHMJIT SINGH</strong> - Professor, Electronics & Communication Engg, National Institute of Technology (NIT) Kurukshetra</li>
                <li><strong>PROF. (DR.) PRAVINDRA KUMAR</strong> - Professor, Biosciences & Bioengineering, Indian Institute of Technology (IIT) Roorkee</li>
                <li><strong>PROF. (DR.) RUPESH KUMAR PATI</strong> - Professor, Decision Sciences and Operations Management, Indian Institute of Management (IIM) Kozhikode</li>
                <li><strong>DR. ANIRUDDHA KRISHNA PAL</strong> - Senior Deputy General Manager, Bharat Heavy Electricals Limited (BHEL)</li>
                <li><strong>PROF. (DR.) AJAY KUMAR</strong> - Vice Chancellor, Dev Bhoomi Uttarakhand University (DBUU)</li>
                <li><strong>PROF. (DR.) BHANU PRATAP SINGH</strong> - Vice Chancellor, Maharishi University of Information Technology (MUIT), Lucknow, Uttar Pradesh.</li>
                <li><strong>PROF. (DR.) BUTA SINGH SIDHU</strong> - Vice Chancellor, Shobhit University, Gangoh, Saharanpur</li>
                <li><strong>PROF. (DR.) VINAY GOYAL</strong> - Vice Chancellor, CGC University, Mohali, Punjab</li>
                <li><strong>DR. SHOWKET HUSSAIN</strong> - Sr. Scientist, ICMR- National Institute of Cancer prevention & Research</li>
                <li><strong>DR. GAYACHARAN</strong> - Sr. Scientist, ICAR- National Bureau of Plant Genetic Resources, New Delhi, India</li>
                <li><strong>DR. CHANCHAL KUMAR</strong> - Sr. Scientist, R&D, Panacea Biotech Delhi, India</li>
                <li><strong>DR. AVISHEK DEY</strong> - Head, Research and Development, GENE2GO Pvt Ltd, Bangalore.</li>
                <li><strong>PROF. (DR.) VIJAY KUMAR GUPTA</strong> - Principal Incharge, Government Engineering College, West Champaran, Bihar</li>
            </ul>
            </div>`;
}

function renderEditorialBoard() {
    return `<div class="card"><div class="section-title">Editorial Board</div>
            <ul style="margin-left:1.2rem; margin-top:0.5rem; list-style:none;">
                <li><strong>DR. SAURABH AGRAWAL</strong> - Associate Professor & Head of Department, DSM, Delhi Technological University, Delhi (saurabh.agrawal@dtu.ac.in)</li>
                <li><strong>DR. NIDHI TANWAR</strong> - Assistant Professor, Punjab Engineering College (Deemed to be University), Chandigarh, Punjab (nidhitanwar@pec.edu.in)</li>
                <li><strong>DR. ANITA KUMARI</strong> - Assistant Professor, Department of Botany, Mahatma Gandhi PG College, Gorakhpur, U.P. (anitamaurya913@gmail.com)</li>
                <li><strong>DR. VIJAY PRAKASH GUPTA</strong> - Associate Professor, GLA University, Mathura (vijay.gupta@gla.ac.in)</li>
                <li><strong>DR. AZATULLAH ZAHEER</strong> - Assistant Professor, Department of Business Administration, Salam University, Kabul, Afghanistan. (azatullahzaheer28@salam.edu.af)</li>
                <li><strong>PROF. (DR.) MANORAMA</strong> - Principal, Education, HIMT, Greater Noida (himteducation8@gmail.com)</li>
                <li><strong>PROF. (DR.) DINESH KUMAR</strong> - HOD, Department of Biotechnology, HIMT, Greater Noida (drdineshkumarnanotech@gmail.com)</li>
                <li><strong>DR. MOHIT TYAGI</strong> - Associate Professor, Punjab Engineering College (Deemed to be University), Chandigarh, Punjab (tyagim@pec.edu.in)</li>
                <li><strong>DR. DEO PRAKASH</strong> - Assistant Professor, School of Computer Science and Engineering, Shri Mata Vaishno Devi University, Katra, Jammu (deoprakash@smvdu.ac.in)</li>
                <li><strong>DR. PUSHPA SINGH</strong> - Associate Professor, Bennett University, Greater Noida (pushpa@bennett.edu.in)</li>
                <li><strong>DR. NARENDRA SINGH</strong> - Associate Professor, GL Bajaj Institute of Technology & Management, Greater Noida (narendra.singh@glbitm.ac.in)</li>
                <li><strong>DR. MANISHA KUMAR</strong> - Professor, Galgotias Institute of Technology and Management, Greater Noida (manisha.kumar@galgotiacollege.edu)</li>
                <li><strong>PROF. NARENDRA UPADHYAY</strong> - HOD, Department of Computer Applications, HIMT, Greater Noida (narendral6may@gmail.com)</li>
                <li><strong>PROF. (DR.) ANUJ SHEOPURI</strong> - Professor, Department of Management Studies, HIMT, Greater Noida (dr.anujsheopuri@gmail.com)</li>
            </ul>
            </div>`;
}

function renderCurrentIssue() {
    let articlesHtml = '';
    articlesData.slice(0, 3).forEach((art, idx) => {
        articlesHtml += `<div class="article-item"><div class="article-title">${art.title}</div><div class="article-authors">${art.authors}</div><div class="article-meta">First Published: ${art.date} | pp. ${art.pages}</div><button class="pdf-btn" onclick="handlePdfDownload(${idx}, '${art.title.replace(/'/g, "\\'")}')"><i class="fas fa-download"></i> Download PDF</button></div>`;
    });
    return `<div class="card">
            <p><strong>Editor-in-Chief:</strong> Prof. (Dr) Sudhir Kumar, HIMT, Greater Noida</p>
            <p><strong>Current Status:</strong> Online & Print on Demand</p>
            ${articlesHtml}
            <p style="margin-top:1rem;"><strong>Address for Correspondence:</strong><br>Harlal Institute Of Management And Technology, Knowledge Park I, Greater Noida - 201310, India.</p>
            </div>`;
}

function renderAllIssues() {
    return `<div class="card">
            </div>`;
}

function renderOnlineFirst() {
    return `<div class="card"><div class="section-title">Online First (Articles ahead of print)</div>
            
            </div>`;
}

function renderSubmissionGuidelines() {
    return `
    <div class="card">

        <div class="section-title">
            Call for Papers
        </div>

        <p>
            The Editorial Board invites authors to carefully follow the
            guidelines below before submitting manuscripts for publication
            consideration.
        </p>

        <br>

        <h3>Scope of the Journal</h3>

        <p>
            HJMST publishes original research papers, review articles,
            case studies, and conceptual papers in the areas of:
        </p>

        <ul style="margin-left:1.2rem;">
            <li>Management & Commerce</li>
            <li>Science & Technology</li>
            <li>Computer Applications & IT</li>
            <li>Artificial Intelligence & Data Analytics</li>
            <li>Biotechnology</li>
            <li>Education</li>
            <li>Interdisciplinary and Emerging Research Areas</li>
        </ul>

        <br>

        <h3>Manuscript Preparation</h3>

        <ul style="margin-left:1.2rem;">
            <li>Manuscripts must be original and unpublished.</li>
            <li>The paper should not be under consideration for publication elsewhere.</li>
            <li>Language: English (clear, concise and grammatically correct).</li>
            <li>Font: Times New Roman, 12-point size.</li>
            <li>Line Spacing: 1.5</li>
            <li>Margins: 1 inch on all sides.</li>
            <li>Word Limit: 3,000–6,000 words (including references).</li>
        </ul>

        <br>

        <h3>Structure of the Paper</h3>

        <p>Manuscripts should be arranged in the following order:</p>

        <ol style="margin-left:1.2rem;">
            <li>Title of the Paper (16-point size)</li>
            <li>Author(s) Name, Affiliation, Email ID</li>
            <li>Abstract (150–250 words)</li>
            <li>Keywords (4–6 keywords)</li>
            <li>Introduction</li>
            <li>Literature Review</li>
            <li>Research Methodology</li>
            <li>Results & Discussion</li>
            <li>Conclusion & Implications</li>
            <li>Acknowledgement (if any)</li>
            <li>References</li>
        </ol>

        <br>

        <h3>Referencing Style</h3>

        <ul style="margin-left:1.2rem;">
            <li>Authors must follow APA (Latest Edition) referencing style.</li>
            <li>All in-text citations must appear in the reference list and vice versa.</li>
        </ul>

        <br>

        <h3>Plagiarism Policy</h3>

        <ul style="margin-left:1.2rem;">
            <li>The manuscript must have a similarity index within acceptable academic limits (preferably below 10–15%, excluding references).</li>
            <li>Proper citation of all sources is mandatory.</li>
        </ul>

        <br>

        <h3>Peer Review Process</h3>

        <ul style="margin-left:1.2rem;">
            <li>All submissions will undergo a peer review process.</li>
            <li>The decision of the Editorial Board regarding acceptance, revision or rejection shall be final.</li>
        </ul>

        <br>

        <h3>Submission Process</h3>

        <ul style="margin-left:1.2rem;">
            <li>Manuscripts must be submitted in MS Word format.</li>
            <li>Authors should submit their paper via the official journal email ID:
                <strong>hjmst@himt.ac.in</strong>
            </li>
            <li>A declaration of originality and copyright transfer (if accepted) will be required.</li>
        </ul>

        <br>

        <h3>Ethical Standards</h3>

        <ul style="margin-left:1.2rem;">
            <li>Authors must ensure ethical research practices.</li>
            <li>Any conflict of interest must be disclosed.</li>
            <li>Data fabrication, falsification, or unethical practices will lead to immediate rejection.</li>
        </ul>

        <br>

        <h3>Publication Frequency</h3>

        <ul style="margin-left:1.2rem;">
            <li>HJMST is published bi-annually:</li>
            <li>January–June</li>
            <li>July–December</li>
        </ul>
        
        <br>

<h3>Evaluation Criteria</h3>

<p>
    Reviewers evaluate manuscripts based on:
</p>

<ul style="margin-left:1.2rem;">
    <li>Originality and contribution to knowledge.</li>
    <li>Research methodology and rigor.</li>
    <li>Theoretical and practical relevance.</li>
    <li>Clarity of objectives and conclusions.</li>
    <li>Literature review adequacy.</li>
    <li>Data analysis and interpretation.</li>
    <li>Language, structure, and presentation.</li>
</ul>

<br>

<h3>Review Outcomes</h3>

<p>
    Based on reviewers' recommendations, the Editorial Board may decide:
</p>

<ul style="margin-left:1.2rem;">
    <li>Accept without revisions.</li>
    <li>Accept with minor revisions.</li>
    <li>Revise and resubmit (major revisions).</li>
    <li>Reject.</li>
</ul>

<p>
    Authors are required to submit a revised manuscript along with a
    detailed response to reviewers' comments within the stipulated time.
</p>

<br>

<h3>Ethical Standards</h3>

<ul style="margin-left:1.2rem;">
    <li>The journal follows established academic publishing ethics.</li>
    <li>Plagiarism, data fabrication, and duplicate submissions are strictly prohibited.</li>
    <li>Authors must ensure that their work is original and properly cited.</li>
    <li>Any conflict of interest must be disclosed.</li>
</ul>

<br>

<h3>Final Decision</h3>

<p>
    The final decision regarding publication rests with the
    Editor-in-Chief / Editorial Board, based on reviewers'
    recommendations and compliance with journal standards.
</p>

<br>

<h3>Timeline</h3>

<p>
    The journal strives to complete the review process within a
    reasonable time frame (generally 4–6 weeks), subject to reviewer
    availability.
</p>

<br>

<h3>Copyright Notice</h3>

<p>
    Copyright of all published articles in the HIMT Journal of
    Management, Science and Technology (HJMST) remains with the
    author(s). The journal retains the right to publish, reproduce,
    and distribute the work for academic and indexing purposes.
</p>

<br>

<br>

<h3>Disclaimer</h3>

<p>
    The views expressed in the articles published in the HIMT Journal
    of Management, Science & Technology (HJMST) are solely those of
    the authors and do not necessarily reflect the views of the
    Editorial Board or Harlal Institute of Management & Technology, Greater Noida.
</p>

<p>
    The journal is not responsible for any errors, omissions, or
    consequences arising from the use of published content.
    Responsibility for originality and ethical compliance rests
    entirely with the authors.
</p>

        

        <button class="btn-primary"
                style="margin-top:20px;"
                id="submitPaperBtn">
            <i class="fas fa-pen-alt"></i>
            Submit Paper
        </button>

    </div>`;
}

function renderContact() {
    return `<div class="card"><div class="section-title">Contact Us</div>
            <p><i class="fas fa-map-marker-alt"></i> <strong>Editorial Office:</strong></p>
            <p>Block-A, Harlal Institute Of Management And Technology<br>8, Institutional Area, Knowledge Park I, Greater Noida, Gautam Buddh Nagar (UP) 201310</p>
            <p><i class="fas fa-phone-alt"></i> <strong>Phone:</strong> +91-8506032691</p>
            <p><i class="fas fa-envelope"></i> <strong>Email:</strong> hjmst@himt.ac.in</p>
            <p><strong>Office Hours:</strong> Monday-Friday 9:30 AM - 5:30 PM IST</p>
            <p><strong>Editor-in-Chief:</strong> Prof. (Dr) Sudhir Kumar</p>
            <p><strong>Managing Editor:</strong> Prof. (Dr) Pankaj Kumar</p>
            </div>`;
}

function loadPage(pageId, subPage = null) {
    const contentDiv = document.getElementById('dynamicContent');
    let mainContent = '';
    
    if (pageId === 'home') {
        mainContent = renderHome();
    } else if (pageId === 'about') {
        if (subPage === 'aims') mainContent = `<div class="articles-section">${renderAimsScope()}</div><div class="sidebar"><div class="card"><h3>Journal Information</h3><p>ISSN: 2395-6789 | e-ISSN: 2582-9624</p><p>Bi-annual Open Access</p><p>Double-Blind Peer Review</p></div></div>`;
        else if (subPage === 'institute') mainContent = `<div class="articles-section">${renderInstitute()}</div><div class="sidebar"><div class="card"><h3>Institute Highlights</h3><p>AICTE Approved | AKTU Affiliated</p><p>NAAC Accredited 'A' Grade</p></div></div>`;
        else if (subPage === 'indexing') mainContent = `<div class="articles-section">${renderIndexing()}</div><div class="sidebar"><div class="card"><h3>Impact Metrics</h3><p>Citation Index: 3.42</p><p>h-index: 18</p></div></div>`;
        else mainContent = `<div class="articles-section">${renderAimsScope()}</div><div class="sidebar"><div class="card"><h3>Journal Information</h3><p>ISSN: 2395-6789</p></div></div>`;
    } else if (pageId === 'editorialAdvisory') {
        mainContent = `<div class="articles-section">${renderEditorialAdvisory()}</div><div class="sidebar"><div class="card"><h3>Review Process</h3><p>Double-blind peer review with 2-3 external reviewers. Initial decision within 4-6 weeks.</p></div></div>`;
    } else if (pageId === 'editorialBoard') {
        mainContent = `<div class="articles-section">${renderEditorialBoard()}</div><div class="sidebar"><div class="card"><h3>Review Process</h3><p>Double-blind peer review with 2-3 external reviewers. Initial decision within 4-6 weeks.</p></div></div>`;
    } else if (pageId === 'issues') {
        if (subPage === 'current') mainContent = `<div class="articles-section">${renderCurrentIssue()}</div></div>`;
        else if (subPage === 'allissues') mainContent = `<div class="articles-section">${renderAllIssues()}</div><div class="sidebar"><div class="card"><h3>Archive Access</h3><p>All back issues available for registered users. Issues from Volume 17, Issue 1, 2026 onwards can be accessed through the website.</p></div></div>`;
        else if (subPage === 'onlinefirst') mainContent = `<div class="articles-section">${renderOnlineFirst()}</div><div class="sidebar"><div class="card"><h3>Early Access</h3><p>Articles published online before print inclusion. These articles are fully citable.</p></div></div>`;
        else mainContent = `<div class="articles-section">${renderCurrentIssue()}</div><div class="sidebar"><div class="card"><h3>Volume Info</h3><p>Current Volume: 4 | Issue: 1</p></div></div>`;
    } else if (pageId === 'guidelines') {
        mainContent = `<div class="articles-section">${renderSubmissionGuidelines()}</div><div class="sidebar"><div class="card"><h3>Support</h3><p>Email: hjmst@himt.ac.in</p><p>Phone: +91-8506032691</p></div></div>`;
        setTimeout(() => {
            document.getElementById('submitPaperBtn')?.addEventListener('click', () => { if(!currentUser) openModal(); else alert("Submit your paper via online submission portal. Email hjmst@himt.ac.in"); });
        }, 100);
    } else if (pageId === 'contact') {
        mainContent = `<div class="articles-section">${renderContact()}</div><div class="sidebar"><div class="card"><h3>Social Media</h3><p><i class="fab fa-instagram"></i> himt_group_of_institutions<br><i class="fab fa-linkedin"></i> himt-group-of-institutions<br><i class="fab fa-facebook"></i> HIMT Group of Institutions, Greater Noida</p></div></div>`;
    }
    
    contentDiv.innerHTML = mainContent;
    
    // Update active class
    document.querySelectorAll('.nav-links > li > a').forEach(link => {
        link.classList.remove('active');
        if(link.getAttribute('data-page') === pageId) link.classList.add('active');
    });
    window.scrollTo({ top: 300, behavior: 'smooth' });
}

// Event Listeners for main navigation
document.querySelectorAll('.nav-links > li > a').forEach(link => {
    const page = link.getAttribute('data-page');
    if (page && page !== 'about' && page !== 'issues') {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            loadPage(page);
        });
    }
});

// Dropdown items for About
document.querySelectorAll('#aboutDropdown a').forEach(item => {
    item.addEventListener('click', (e) => {
        e.preventDefault();
        const page = item.getAttribute('data-page');
        const subpage = item.getAttribute('data-subpage');
        if (page && subpage) loadPage(page, subpage);
    });
});

// Dropdown items for Issues
document.querySelectorAll('#issuesDropdown a').forEach(item => {
    item.addEventListener('click', (e) => {
        e.preventDefault();
        const page = item.getAttribute('data-page');
        const subpage = item.getAttribute('data-subpage');
        if (page && subpage) loadPage(page, subpage);
    });
});

// Prevent parent link from navigating on About and Issues main buttons
document.getElementById('aboutMainBtn')?.addEventListener('click', (e) => {
    e.preventDefault();
});
document.getElementById('issuesMainBtn')?.addEventListener('click', (e) => {
    e.preventDefault();
});

document.getElementById('callToActionBtn').addEventListener('click', () => {
    //temporary leave
});
document.querySelector('.close-modal').addEventListener('click', closeModal);
window.onclick = (e) => { if(e.target === document.getElementById('authModal')) closeModal(); };

// Login/Register Handlers
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const tab = btn.dataset.tab;
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        if(tab === 'login') {
            document.getElementById('loginFormContainer').style.display = 'block';
            document.getElementById('registerFormContainer').style.display = 'none';
        } else {
            document.getElementById('loginFormContainer').style.display = 'none';
            document.getElementById('registerFormContainer').style.display = 'block';
        }
    });
});

document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    const res = await apiRequest('login', { email, password });
    if(res.success) {
        closeModal();
        checkSession();
        alert("Login successful! You can now download PDFs.");
        location.reload();
    } else {
        document.getElementById('loginError').innerText = res.error || "Invalid credentials";
    }
});

document.getElementById('registerForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const name = document.getElementById('regName').value;
    const email = document.getElementById('regEmail').value;
    const phone = document.getElementById('regPhone').value;
    const institute = document.getElementById('regInstitute').value;
    const password = document.getElementById('regPassword').value;
    if(password.length < 6) { document.getElementById('regError').innerText = "Password must be at least 6 characters"; return; }
    if(!/^\d{10}$/.test(phone)) { document.getElementById('regError').innerText = "Phone number must be 10 digits"; return; }
    const res = await apiRequest('register', { name, email, phone, institute, password });
    if(res.success) {
        document.getElementById('regSuccess').innerHTML = "Registration successful! Please login.";
        document.getElementById('registerForm').reset();
        setTimeout(() => { document.querySelector('.tab-btn[data-tab="login"]').click(); }, 1500);
    } else {
        document.getElementById('regError').innerText = res.error || "Registration failed. Email may already exist.";
    }
});

checkSession();
loadPage('home');
</script>
</body>
</html>