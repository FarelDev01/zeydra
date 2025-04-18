<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Zeydra.Ai</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="icon" href="page/image/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/monokai.min.css">
    <link rel="icon" href="https://pomf2.lain.la/f/0nvy9oto.jpg">
    <style>
        /* Import Google Font - Poppins */
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap");

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

:root {
            /* Dark theme colors */
            --text-color: #edf3ff;
            --subheading-color: #97a7ca;
            --placeholder-color: #c3cdde;
            --primary-color: #101623;
            --secondary-color: #283045;
            --secondary-hover-color: #333e58;
            --scrollbar-color: #626a7f;
        }

        body.light-theme {
            /* Light theme colors */
            --text-color: #090c13;
            --subheading-color: #7b8cae;
            --placeholder-color: #606982;
            --primary-color: #f3f7ff;
            --secondary-color: #dce6f9;
            --secondary-hover-color: #d2ddf2;
            --scrollbar-color: #a2aac2;
        }

        body {
            color: var(--text-color);
            background: var(--primary-color);
        }

        .container {
            overflow-y: auto;
            padding: 32px 0 60px;
            height: calc(100vh - 127px);
            scrollbar-color: var(--scrollbar-color) transparent;
        }


        .container :where(.app-header, .suggestions, .message, .prompt-wrapper) {
            position: relative;
            margin: 0 auto;
            width: 100%;
            padding: 0 20px;
            max-width: 990px;
        }

        .container .app-header {
            margin-top: 0vh;
        }

        pre {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 5px;
            border-radius: 8px;
            overflow-x: auto;
            white-space: pre-wrap;
            font-family: 'Fira Code', monospace;
            font-size: 14px;
            word-break: word-break;
        }

        code {
            display: block;
            padding: 5px;
        }


        .app-header .heading {
            width: fit-content;
            font-size: 3rem;
            background: linear-gradient(to right, #1d7efd, #8f6fff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .app-header .sub-heading {
            font-size: 2.6rem;
            margin-top: -5px;
            color: var(--subheading-color);
        }

        .container .suggestions {
            width: 100%;
            list-style: none;
            display: flex;
            gap: 15px;
            margin-top: 9.5vh;
            text-align: left;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
        }

        body.chats-active .container :where(.app-header, .suggestions) {
            display: none;
        }

        .suggestions .suggestions-item {
            cursor: pointer;
            padding: 18px;
            width: 228px;
            flex-shrink: 0;
            display: flex;
            scroll-snap-align: center;
            flex-direction: column;
            align-items: flex-end;
            border-radius: 12px;
            justify-content: space-between;
            background: var(--secondary-color);
            transition: 0.3s ease;

        }

        .suggestions .suggestions-item p {
            -webkit-hyphens: auto;
            -moz-hyphens: auto;
            -ms-hyphens: auto;
            hyphens: auto;
            word-break: break-word;
            width: 100%;
            text-align-last: left;
        }

        .suggestions .suggestions-item:hover {
            background: var(--secondary-hover-color);
        }

        .suggestions .suggestions-item .text {
            font-size: 1.1rem;
        }

        .suggestions .suggestions-item .icon {
            width: 45px;
            height: 45px;
            display: flex;
            font-size: 1.4rem;
            margin-top: 35px;
            align-self: flex-end;
            align-items: center;
            border-radius: 50%;
            justify-content: center;
            color: #1d7efd;
            background: var(--primary-color);
        }

        .suggestions .suggestions-item:nth-child(2) .icon {
            color: #28a745;
        }

        .suggestions .suggestions-item:nth-child(3) .icon {
            color: #ffc107;
        }

        .suggestions .suggestions-item:nth-child(4) .icon {
            color: #6f42c1;
        }

        .container .chats-container {
            display: flex;
            gap: 20px;
            flex-direction: column;
        }

        .chats-container .message {
            display: flex;
            gap: 11px;
            align-items: center;
        }

        .chats-container .message .avatar {
            width: 43px;
            height: 43px;
            flex-shrink: 0;
            align-self: flex-start;
            border-radius: 50%;
            padding: 6px;
            margin-right: -7px;
            background: var(--secondary-color);
            border: 1px solid var(--secondary-hover-color);
        }

        .chats-container .message.loading .avatar {
            animation: rotate 3s linear infinite;
        }



@keyframes rotate {
            100% {
                transform: rotate(360deg);
            }
        }

        .chats-container .message .message-text {
            padding: 3px 16px;
            word-wrap: break-word;
            white-space: pre-line;
            word-break: break-word;
            margin: 5px 0;
        }

        .chats-container .bot-message {
            margin: 9px auto;
        }

        .chats-container .user-message {
            flex-direction: column;
            align-items: flex-end;
        }

        .chats-container .user-message .message-text {
            padding: 12px 16px;
            max-width: 75%;
            background: var(--secondary-color);
            border-radius: 13px 13px 3px 13px;
        }

        .chats-container .user-message .img-attachment {
            margin-top: -7px;
            width: 50%;
            border-radius: 13px 3px 13px 13px;
        }

        .chats-container .user-message .file-attachment {
            display: flex;
            gap: 6px;
            align-items: center;
            padding: 10px;
            margin-top: -7px;
            border-radius: 13px 3px 13px 13px;
            background: var(--secondary-color);
        }

        .chats-container .user-message .file-attachment span {
            color: #1d7efd;
        }

        .container .prompt-container {
            position: fixed;
            width: 100%;
            left: 0;
            bottom: 0;
            padding: 16px 0;
            background: var(--primary-color);
        }

        .prompt-container :where(.prompt-wrapper, .prompt-form, .prompt-actions) {
            display: flex;
            gap: 12px;
            height: 56px;
            align-items: center;
        }

        .prompt-container .prompt-form {
            height: 100%;
            width: 100%;
            border-radius: 130px;
            background: var(--secondary-color);
        }

        .prompt-form .prompt-input {
            width: 100%;
            height: 100%;
            background: none;
            outline: none;
            border: none;
            font-size: 1rem;
            color: var(--text-color);
            padding-left: 24px;
        }


        .prompt-form .prompt-input::placeholder {
            color: var(--placeholder-color);
        }

        .prompt-wrapper button {
            width: 56px;
            height: 100%;
            flex-shrink: 0;
            cursor: pointer;
            border-radius: 50%;
            font-size: 1.4rem;
            border: none;
            color: var(--text-color);
            background: var(--secondary-color);
            transition: 0.3s ease;
        }

        .prompt-wrapper :is(button:hover, #cancel-file-btn, .file-icon) {
            background: var(--secondary-hover-color);
        }

        .prompt-form .prompt-actions {
            gap: 5px;
            margin-right: 7px;
        }

        .prompt-wrapper .prompt-form :where(.file-upload-wrapper, button, img) {
            position: relative;
            height: 45px;
            width: 45px;
        }

        .prompt-form .prompt-actions #send-prompt-btn {
            color: #fff;
            display: none;
            background: #1d7efd;
        }

        .prompt-form .prompt-input:valid~.prompt-actions #send-prompt-btn {
            display: block;
        }

        .prompt-form #send-prompt-btn:hover {
            background: #0264e3;
        }

        .prompt-form .file-upload-wrapper :where(button, img) {
            display: none;
            border-radius: 50%;
            object-fit: cover;
            position: absolute;
        }

        .prompt-form .file-upload-wrapper.active #add-file-btn {
            display: none;
        }

        .prompt-form .file-upload-wrapper #add-file-btn,
        .prompt-form .file-upload-wrapper.active.img-attached img,
        .prompt-form .file-upload-wrapper.active.file-attached .file-icon,
        .prompt-form .file-upload-wrapper.active:hover #cancel-file-btn {
            display: block;
        }

        .prompt-form :is(#stop-response-btn:hover, #cancel-file-btn) {
            color: #d62939;
        }

        .prompt-wrapper .prompt-form .file-icon {
            color: #1d7efd;
        }

        .prompt-form #stop-response-btn,
        body.bot-responding .prompt-form .file-upload-wrapper {
            display: none;
        }

        body.bot-responding .prompt-form #stop-response-btn {
            display: block;
        }

        .prompt-container .disclaimer-text {
            font-size: 0.9rem;
            text-align: center;
            padding: 16px 20px 0;
            color: var(--placeholder-color);
        }

        /* Responsive media query code for small screens */
@media (max-width: 768px) {
            .container {
                padding: 20px 0 100px;
            }

            .app-header :is(.heading, .sub-heading) {
                font-size: 2rem;
                line-height: 1.4;
            }

            .app-header .sub-heading {
                font-size: 1.7rem;
            }

            .container .chats-container {
                gap: 15px;
            }

            .chats-container .bot-message {
                margin: 4px auto;
            }

            .prompt-container :where(.prompt-wrapper, .prompt-form, .prompt-actions) {
                gap: 8px;
                height: 53px;
            }

            .prompt-container button {
                width: 53px;
            }

            .prompt-form :is(.file-upload-wrapper, button, img) {
                height: 42px;
                width: 42px;
            }

            .prompt-form .prompt-input {
                padding-left: 20px;
            }

            .prompt-form .file-upload-wrapper.active #cancel-file-btn {
                opacity: 0;
            }

            .prompt-wrapper.hide-controls :where(#theme-toggle-btn, #delete-chats-btn) {
                display: none;
            }
        }

        .header {
            position: relative;
            top: 0;
            left: 0;
            margin-bottom: 5vh;
            width: 100%;
            background: var(--primary-color);
            z-index: 10005;
            padding: 1rem;
            border-bottom: 2px solid var(--secondary-color);
            border-radius: 10px;
        }

        button[id=back] {
            position: absolute;
            top: 0;
            right: 0;
            height: 100%;
            width: auto;
            background: transparent;
            font-size: 25px;
            border: none;
            color: var(--text-color);
            padding: 1rem 2.5rem;
        }

        #back:active {
            transform: scale(0.9);
            color: var(--secondary-hover-color);
        }

        button[id=login] {
            position: absolute;
            top: 0;
            right: 3.5rem;
            height: 100%;
            width: auto;
            background: transparent;
            font-size: 25px;
            border: none;
            color: var(--text-color);
            padding: 1rem 2.5rem;
        }

        #login:active {
            transform: scale(0.9);
            color: var(--secondary-hover-color);
        }

        .sidebar {
            position: fixed;
            top: 0;
            right: -100%;
            background: var(--secondary-color);
            z-index: 1000;
            width: auto;
            max-width: 25vh;
            transition: 0.5s;
        }

        .active {
            right: 0%;
        }

        .sidebar button {
            padding: 10px;
            width: 100%;
            border: none;
            background: transparent;
            color: var(--text-color);
            font-weight: bold;
            text-align: left;
            font-size: 15px;
        }

        .sidebar button i {
            margin: 0 5px;
        }

        #getButton:active {
            opacity: 50%;
        }

        #getButton {
            color: var(--text-color);
        }
        .popup-container {
            display: none;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <div class="sidebar">
                <button id="getButton" onclick="button('close')"><i class="fa fa-arrow-right"></i></button>
                <button id="getButton" onclick="button('credit')"><i class="fa fa-user-circle"></i>Credit</button>
                <button id="getButton" onclick="button('share')"><i class="fa fa-share"></i>Share</button>
                <button id="getButton" onclick="button('folow')"><i class="fa fa-user-plus"></i>Folow</button>
                <button id="getButton" onclick="button('wabot')"><i class="fab fa-whatsapp"></i>Whatsapp</button>
                <button id="getButton" onclick="button('donate')"><i class="fas fa-donate"></i>Donate</button>
            </div>
            <h1>Hello</h1>
            <button onclick="showside()" id="back"><i class="fas fa-bars"></i></button>
            <button onclick="window.location='profile.php'" id="login"><i class="fa fa-user"></i></button>
            <p>
                Zeydra 1.5 Flash
            </p>
        </div>
        <header class="app-header">
            <h1 class="heading">Hello, <span id="nama-user"></span></h1>
            <h4 class="sub-heading">How can I help you today?</h4>
        </header>

        <!-- Suggestions List -->
        <ul class="suggestions"></ul>
        <ul class="suggestions"></ul>
        <!-- Chats -->
        <div class="chats-container"></div>

        <!-- Prompt Input -->
        <div class="prompt-container">
            <div class="prompt-wrapper">
                <form action="#" class="prompt-form">
                    <input type="text" placeholder="Ask Zeydra Ai" class="prompt-input" required />
                    <div class="prompt-actions">
                        <div class="file-upload-wrapper">
                            <img src="#" class="file-preview" />
                            <input id="file-input" type="file" accept=".png,.jpg,.jpeg,.mp3,.mp4" hidden>
                            <button type="button" class="file-icon material-symbols-rounded"><i class="fa fa-video"></i></button>
                            <button id="cancel-file-btn" type="button" class="material-symbols-rounded">close</button>
                            <button id="add-file-btn" type="button" class="material-symbols-rounded"><i class="fas fa-upload"></i></button>
                        </div>

                        <!-- Send Prompt and Stop Response Buttons -->
                        <button id="stop-response-btn" type="button" class="material-symbols-rounded"><i class="fa fa-stop-circle"></i></button>
                        <button id="send-prompt-btn" class="material-symbols-rounded"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </form>

                <!-- Theme and Delete Chats Buttons -->
                <button id="theme-toggle-btn" class="material-symbols-rounded"><i class="fa fa-sun"></i></button>
                <button id="delete-chats-btn" class="material-symbols-rounded"><i class="fas fa-trash-alt"></i></button>
            </div>

            <p class="disclaimer-text">
                Zeydra Ai can make mistakes, so double-check it.
            </p>
        </div>
    </div>

    <div class="popup-container" id="popup">
        <div id="check" class="checkmark"></div>
        <p class="popup-message">
            succesfuly selected
        </p>
        <button onclick="hidePopup()">OK</button>
    </div>
</div>
<script>
    fetch('auth.php?get_user=1')
    .then(response => response.json())
    .then(data => {
        if (data.name) {
            document.getElementById('nama-user').textContent = data.name;
        } else {
            document.getElementById('nama-user').textContent = 'There'
        }
    })
    .catch(error => console.error('Gagal mengambil data user:', error));
</script>

<script>
    const loginText = document.querySelector(".title-text .login");
    const loginForm = document.querySelector("form.login");
    const loginBtn = document.querySelector("label.login");
    const signupBtn = document.querySelector("label.signup");
    const signupLink = document.querySelector("form .signup-link a");
    const questions = {
        en: [
            "What is the capital of France?",
            "Who wrote 'To Kill a Mockingbird'?",
            "What is the chemical symbol for gold?",
            "How many continents are there on Earth?",
            "Who painted the Mona Lisa?",
            "What is the largest planet in our solar system?",
            "Who discovered gravity?",
            "What year did World War II end?",
            "How many sides does a hexagon have?",
            "What is the boiling point of water in Celsius?",
            "Who was the first person to walk on the moon?",
            "What is the currency of Japan?",
            "How many legs does a spider have?",
            "What is the speed of light?",
            "Who invented the telephone?",
            "What is the capital of Canada?",
            "Which is the longest river in the world?",
            "Who wrote the play 'Romeo and Juliet'?",
            "What gas do plants absorb from the atmosphere?",
            "What is the main ingredient in guacamole?",
            "Make A simple Web using Html, css and Js",
            "What is the largest ocean on Earth?",
            "Who developed the theory of relativity?",
            "What is the chemical formula for water?",
            "Which planet is known as the 'Red Planet'?",
            "What is the largest mammal in the world?",
            "Who was the first president of the United States?",
            "What is the process by which plants make their own food called?",
            "What is the function of the human heart?",
            "Which gas makes up the majority of Earth's atmosphere?",
            "Who invented the light bulb?",
            "Explain the concept of quantum entanglement and its potential applications.",
            "Describe the process of nuclear fusion and its role in stellar energy production.",
            "What are the implications of dark matter and dark energy on the structure of the universe?",
            "Discuss the principles of thermodynamics and their relevance to energy efficiency.",
            "Explain the theory of general relativity and its predictions about spacetime.",
            "What is the Higgs boson and why is it important in particle physics?",
            "Describe the process of photosynthesis and its significance for life on Earth.",
            "Explain the principles of genetic engineering and its potential impact on medicine and agriculture.",
            "What are the challenges and opportunities in developing sustainable energy sources?",
            "Discuss the ethical considerations surrounding artificial intelligence and its future development."
        ],
        id: [
            "Apa ibu kota Prancis?",
            "Siapa yang menulis 'To Kill a Mockingbird'?",
            "Apa simbol kimia untuk emas?",
            "Berapa banyak benua di Bumi?",
            "Siapa yang melukis Mona Lisa?",
            "Apa planet terbesar di tata surya kita?",
            "Siapa yang menemukan gravitasi?",
            "Tahun berapa Perang Dunia II berakhir?",
            "Berapa sisi yang dimiliki oleh segi enam?",
            "Berapa titik didih air dalam Celsius?",
            "Siapa orang pertama yang berjalan di bulan?",
            "Apa mata uang Jepang?",
            "Berapa banyak kaki yang dimiliki laba-laba?",
            "Berapa kecepatan cahaya?",
            "Siapa yang menemukan telepon?",
            "Apa ibu kota Kanada?",
            "Sungai apa yang terpanjang di dunia?",
            "Siapa yang menulis drama 'Romeo dan Juliet'?",
            "Gas apa yang diserap oleh tumbuhan dari atmosfer?",
            "Apa bahan utama dalam guacamole?",
            "Buat web sederhana menggunakan html, css dan js",
            "Apa samudra terbesar di Bumi?",
            "Siapa yang mengembangkan teori relativitas?",
            "Apa rumus kimia untuk air?",
            "Planet mana yang dikenal sebagai 'Planet Merah'?",
            "Apa mamalia terbesar di dunia?",
            "Siapa presiden pertama Amerika Serikat?",
            "Apa nama proses tumbuhan membuat makanannya sendiri?",
            "Apa fungsi jantung manusia?",
            "Gas apa yang membentuk sebagian besar atmosfer Bumi?",
            "Siapa yang menemukan bola lampu?",
            "Jelaskan konsep keterikatan kuantum dan aplikasi potensialnya.",
            "Jelaskan proses fusi nuklir dan perannya dalam produksi energi bintang.",
            "Apa implikasi materi gelap dan energi gelap terhadap struktur alam semesta?",
            "Diskusikan prinsip-prinsip termodinamika dan relevansinya dengan efisiensi energi.",
            "Jelaskan teori relativitas umum dan prediksinya tentang ruang-waktu.",
            "Apa itu boson Higgs dan mengapa penting dalam fisika partikel?",
            "Jelaskan proses fotosintesis dan signifikansinya bagi kehidupan di Bumi.",
            "Jelaskan prinsip-prinsip rekayasa genetika dan dampak potensialnya pada kedokteran dan pertanian.",
            "Apa tantangan dan peluang dalam mengembangkan sumber energi berkelanjutan?",
            "Diskusikan pertimbangan etika seputar kecerdasan buatan dan pengembangan masa depannya."
        ]
    };


    const icons = {
        "fa-map-marker-alt": ["capital",
            "city",
            "country",
            "ibu kota"],
        "fa-book": ["wrote",
            "play",
            "author",
            "menulis",
            "drama"],
        "fa-flask": ["chemical",
            "gas",
            "boiling",
            "kimia",
            "titik didih"],
        "fa-globe": ["continent",
            "planet",
            "world",
            "benua",
            "tata surya"],
        "fa-paint-brush": ["paint",
            "Mona Lisa",
            "melukis"],
        "fa-history": ["discovered",
            "year",
            "WWII",
            "menemukan",
            "Perang Dunia"],
        "fa-shapes": ["hexagon",
            "sides",
            "geometry",
            "segi enam",
            "sisi"],
        "fa-thermometer-half": ["temperature",
            "boiling",
            "titik didih",
            "suhu"],
        "fa-space-shuttle": ["moon",
            "light",
            "speed",
            "bulan",
            "cahaya",
            "kecepatan"],
        "fa-leaf": ["plants",
            "absorb",
            "photosynthesis",
            "tumbuhan",
            "menyerap"],
        "fa-code": ["code",
            "html",
            " css",
            "Js"]
    };

    function getIcon(question) {
        for (const [icon, keywords] of Object.entries(icons)) {
            if (keywords.some(keyword => question.toLowerCase().includes(keyword))) {
                return icon;
            }
        }
        return "fa-question-circle";
    }

    function detectLanguage() {
        const lang = navigator.language.startsWith("id") ? "id": "en";
        return lang;
    }

    // Shuffle array to get random items
    function shuffle(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i],
                array[j]] = [array[j],
                array[i]];
        }
    }

    // Select two <ul class="suggestions">
    const suggestionLists = document.querySelectorAll("ul.suggestions");
    if (suggestionLists.length < 2) {
        console.error("There should be at least two <ul class='suggestions'> elements.");
    } else {
        const lang = detectLanguage();
        shuffle(questions[lang]);

        const uniqueQuestions = questions[lang].slice(0, 30);
        const half = Math.ceil(uniqueQuestions.length / 2);
        const firstHalf = uniqueQuestions.slice(0, half);
        const secondHalf = uniqueQuestions.slice(half);

        function appendQuestions(list, questions) {
            list.innerHTML = ""; // Clear existing items
            questions.forEach(question => {
                const li = document.createElement("li");
                li.className = "suggestions-item";
                const icon = getIcon(question);
                li.innerHTML = `
                <p class="text">${question}</p>
                <span class="icon material-symbols-rounded">
                <i class="fa ${icon}"></i>
                </span>
                `;
                list.appendChild(li);
            });
        }

        appendQuestions(suggestionLists[0], firstHalf);
    }

    function showside() {
        document.querySelector('.sidebar').classList.add('active')
    }

    function button(button) {
        if (button == 'close') {
            document.querySelector('.sidebar').classList.remove('active')
        } else if (button == 'credit') {
            window.location.href = 'page/contact.html'
        } else if (button == 'donate') {
            window.location.href = 'https://saweria.co/FarelAlfareza'
        } else if (button == 'share') {
            let weblink = window.location.href;
            window.open(`https://api.whatsapp.com/send?text=${weblink}`)
        } else if (button == 'wabot') {
            window.open(`https://api.whatsapp.com/send?phone=6287840615800&text=Halo+Kak+Aku+Mau+Nanya+Sesuatu:`)
        } else {
            window.location.href = 'folow.php'
        }
    }

</script>
<script src="ai.js"></script>
<script src="page/data/config.js"></script>
<script src="page/data/user.js"></script>
<script src="page/back.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
</body>

</html>