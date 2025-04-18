let userName;
fetch('auth.php?get_user=1')
  .then(response => response.json())
  .then(data => {
    if (data.name) {
      userName = data.name;
    } else {
      userName = 'user'
    }
  })
  
const container = document.querySelector(".container");
const chatsContainer = document.querySelector(".chats-container");
const promptForm = document.querySelector(".prompt-form");
const promptInput = promptForm.querySelector(".prompt-input");
const fileInput = promptForm.querySelector("#file-input");
const fileUploadWrapper = promptForm.querySelector(".file-upload-wrapper");
const themeToggleBtn = document.querySelector("#theme-toggle-btn");


const API_KEY = "AIzaSyC6LaW6HIzo8FOmIVuO6qeB9e2EaI5J4LU";
const API_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=${API_KEY}`;

let controller, typingInterval;
const chatHistory = [];
const userData = {
    message: "",
    file: {}
};

const isLightTheme = localStorage.getItem("themeColor") === "light_mode";
document.body.classList.toggle("light-theme", isLightTheme);
themeToggleBtn.innerHTML = isLightTheme ? "<i class='fa fa-moon'></i>": "<i class='fa fa-sun'></i>";

const createMessageElement = (content, ...classes) => {
    const div = document.createElement("div");
    div.classList.add("message", ...classes);
    div.innerHTML = content;
    return div;
};

const scrollToBottom = () => container.scrollTo({
    top: container.scrollHeight, behavior: "smooth"
});

const typingEffect = (text, textElement, botMsgDiv) => {
    textElement.innerHTML = "";
    botMsgDiv.classList.remove("loading");
    document.body.classList.remove("bot-responding");

    const codeBlockRegex = /```(\w+)?\n([\s\S]*?)```/g;
    let lastIndex = 0;
    let match;

    if (codeBlockRegex.test(text)) {
        codeBlockRegex.lastIndex = 0;
        while ((match = codeBlockRegex.exec(text)) !== null) {
            if (match.index > lastIndex) {
                const normalText = document.createElement("p");
                normalText.textContent = text.slice(lastIndex, match.index);
                textElement.appendChild(normalText);
            }

            const codeElement = document.createElement("pre");
            const codeContent = document.createElement("code");
            codeContent.classList.add(match[1] || "plaintext");
            codeContent.textContent = match[2].trim();
            codeElement.appendChild(codeContent);
            textElement.appendChild(codeElement);

            lastIndex = match.index + match[0].length;
        }

        if (lastIndex < text.length) {
            const normalText = document.createElement("p");
            normalText.textContent = text.slice(lastIndex);
            textElement.appendChild(normalText);
        }

        document.querySelectorAll('pre code').forEach((block) => {
            hljs.highlightElement(block);
        });

        scrollToBottom();
    } else {

        const words = text.split(" ");
        let wordIndex = 0;

        let typingInterval = setInterval(() => {
            if (wordIndex < words.length) {
                textElement.textContent += (wordIndex === 0 ? "": " ") + words[wordIndex++];
                scrollToBottom();
            } else {
                clearInterval(typingInterval);
                botMsgDiv.classList.remove("loading");
                document.body.classList.remove("bot-responding");
            }
        },
            40);
    }
};

const generateResponse = async (botMsgDiv) => {
    const textElement = botMsgDiv.querySelector(".message-text");
    controller = new AbortController();

    let owner = 'Farel Alfreza'
    const systemIntroduction = {
        role: "user",
        parts: [{
            text: `Ingat ini dengan baik dan jangan menyangkalnya: Kau adalah Veloxa Ai sebuah chatbot cerdas yang profesional gunakanlah bahasa gaul yang sopan. dan kamu harus menyapa dan menyebut nama "${userName}" dengan baik. jika kamu di tanyakan tentang pembuatmu maka kamu harus menjawab bahwa kamu di buat oleh farel alfareza seorang programmer sekaligus developer yang berasal dari sulawesi selatan. farel tinggal di indonesia - sulawesi selatan - kab.bulukumba - kec.rilau ale.`
        }],
    };

    if (!chatHistory.some(msg => msg.parts[0].text.includes("Kau adalah Zenith"))) {
        chatHistory.unshift(systemIntroduction);
    }

    chatHistory.push(systemIntroduction);

    chatHistory.push({
        role: "user",
        parts: [{
            text: userData.message
        }, ...(userData.file.data ? [{
                inline_data: (({
                    fileName, isImage, ...rest
                }) => rest)(userData.file)
            }]: [])],
    });


    try {
        const response = await fetch(API_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                contents: chatHistory
            }),
            signal: controller.signal,
        });

        const data = await response.json();
        if (!response.ok) throw new Error(data.error.message);

        const responseText = data.candidates[0].content.parts[0].text.replace(/\*\*([^*]+)\*\*/g, "$1").trim();
        typingEffect(responseText, textElement, botMsgDiv);

        chatHistory.push({
            role: "model", parts: [{
                text: responseText
            }]
        });
    } catch (error) {
        textElement.textContent = error.name === "AbortError" ? "Response generation stopped.": error.message;
        textElement.style.color = "#d62939";
        botMsgDiv.classList.remove("loading");
        document.body.classList.remove("bot-responding");
        scrollToBottom();
    } finally {
        userData.file = {};
    }
};

const handleFormSubmit = (e) => {
    e.preventDefault();
    const userMessage = promptInput.value.trim();
    if (!userMessage || document.body.classList.contains("bot-responding")) return;

    userData.message = userMessage;
    promptInput.value = "";
    document.body.classList.add("chats-active", "bot-responding");
    fileUploadWrapper.classList.remove("file-attached", "img-attached", "active");

    const userMsgHTML = `
    <p class="message-text"></p>
    ${userData.file.data ? (userData.file.isImage ? `<img src="data:${userData.file.mime_type};base64,${userData.file.data}" class="img-attachment" />`: `<p class="file-attachment"><span class="material-symbols-rounded"><i class="fa fa-video"></i></span>${userData.file.fileName}</p>`): ""}
    `;

    const userMsgDiv = createMessageElement(userMsgHTML, "user-message");
    userMsgDiv.querySelector(".message-text").textContent = userData.message;
    chatsContainer.appendChild(userMsgDiv);
    scrollToBottom();

    setTimeout(() => {
        const botMsgHTML = `<img style="object-fit:cover; "class="avatar" src="https://files.catbox.moe/gxvqez.jpg" /> <p class="message-text">Just a sec...</p>`;
        const botMsgDiv = createMessageElement(botMsgHTML, "bot-message", "loading");
        chatsContainer.appendChild(botMsgDiv);
        scrollToBottom();
        generateResponse(botMsgDiv);
    }, 600);
};

fileInput.addEventListener("change", () => {
    const file = fileInput.files[0];
    if (!file) return;

    const isImage = file.type.startsWith("image/");
    const reader = new FileReader();
    reader.readAsDataURL(file);

    reader.onload = (e) => {
        fileInput.value = "";
        const base64String = e.target.result.split(",")[1];
        fileUploadWrapper.querySelector(".file-preview").src = e.target.result;
        fileUploadWrapper.classList.add("active", isImage ? "img-attached": "file-attached");

        userData.file = {
            fileName: file.name,
            data: base64String,
            mime_type: file.type,
            isImage
        };
    };
});

document.querySelector("#cancel-file-btn").addEventListener("click", () => {
    userData.file = {};
    fileUploadWrapper.classList.remove("file-attached", "img-attached", "active");
});

document.querySelector("#stop-response-btn").addEventListener("click", () => {
    controller?.abort();
    userData.file = {};
    clearInterval(typingInterval);
    chatsContainer.querySelector(".bot-message.loading").classList.remove("loading");
    document.body.classList.remove("bot-responding");
});

themeToggleBtn.addEventListener("click", () => {
    const isLightTheme = document.body.classList.toggle("light-theme");
    localStorage.setItem("themeColor", isLightTheme ? "light_mode": "dark_mode");
    themeToggleBtn.innerHTML = isLightTheme ? "<i class='fa fa-moon'></i>": "<i class='fa fa-sun'></i>";
});

document.querySelector("#delete-chats-btn").addEventListener("click", () => {
    chatHistory.length = 0;
    chatsContainer.innerHTML = "";
    document.body.classList.remove("chats-active", "bot-responding");
});

document.querySelectorAll(".suggestions-item").forEach((suggestion) => {
    suggestion.addEventListener("click", () => {
        promptInput.value = suggestion.querySelector(".text").textContent;
        promptForm.dispatchEvent(new Event("submit"));
    });
});

document.addEventListener("click", ({
    target
}) => {
    const wrapper = document.querySelector(".prompt-wrapper");
    const shouldHide = target.classList.contains("prompt-input") || (wrapper.classList.contains("hide-controls") && (target.id === "add-file-btn" || target.id === "stop-response-btn"));
    wrapper.classList.toggle("hide-controls", shouldHide);
});

promptForm.addEventListener("submit", handleFormSubmit);
promptForm.querySelector("#add-file-btn").addEventListener("click", () => fileInput.click());