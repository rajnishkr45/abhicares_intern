/* =========================================
   1. VARIABLES & SELECTIONS
   ========================================= */
const chatBody = document.getElementById('chatBody');
const userInput = document.getElementById('userInput');
const typingIndicator = document.getElementById('typing');
const menu = document.getElementById('menu');

// Service Data Mappings
const services = {
    en: ['Electrician', 'Plumber', 'Beautician', 'Carpenter'],
    hi: ['बिजली मिस्त्री', 'प्लंबर', 'ब्यूटिशियन', 'बढ़ई']
};

const subCategories = {
    'Electrician': ['Fan Repair', 'Wiring', 'Switch Installation'],
    'Plumber': ['Leakage', 'Tap Fitting', 'Pipe Installation'],
    'Beautician': ['Facial', 'Haircut', 'Makeup'],
    'Carpenter': ['Furniture Repair', 'New Furniture', 'Polishing'],
    'बिजली मिस्त्री': ['Fan Repair', 'Wiring', 'Switch Installation'],
    'प्लंबर': ['Leakage', 'Tap Fitting', 'Pipe Installation'],
    'ब्यूटिशियन': ['Facial', 'Haircut', 'Makeup'],
    'बढ़ई': ['Furniture Repair', 'New Furniture', 'Polishing']
};

// State Management
let state = {
    step: 0,
    name: '',
    language: 'en',
    intent: '',
    category: '',
    subCategory: '',
    city: '',
    address: '',
    phone: ''
};

/* =========================================
   2. INITIALIZATION & UI FUNCTIONS
   ========================================= */
window.onload = () => {
    // There is already a "Hello" in HTML, so we follow up immediately
    setTimeout(() => {
        addBotMessage("Before we start, <strong>please tell me your name?</strong>");
        state.step = 1;
    }, 1000);
};


// Toggle Top Menu
function toggleMenu() {
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
}

// Close menu when clicking ANYWHERE outside the menu or icon
document.addEventListener('click', function (event) {
    const menuIcon = document.querySelector('.menu-icon');

    // If the clicked element is NOT the icon and NOT inside the menu
    if (!menuIcon.contains(event.target) && !menu.contains(event.target)) {
        menu.style.display = 'none';
    }
});

// Handle Enter Key
function handleEnter(event) {
    if (event.key === "Enter") {
        sendMessage();
    }
}

// Main Send Function
function sendMessage() {
    const text = userInput.value.trim();
    if (!text) return;

    addUserMessage(text);
    processFlow(text);
}

// Add User Message
function addUserMessage(text) {
    // --- NEW LOGIC: REMOVE OLD OPTIONS ---
    // This selects all containers with buttons and removes them from the DOM
    const oldOptions = document.querySelectorAll('.options-container');
    oldOptions.forEach(container => container.remove());
    // -------------------------------------

    const msgDiv = document.createElement('div');
    msgDiv.className = 'message user-msg';
    msgDiv.innerText = text;
    chatBody.insertBefore(msgDiv, typingIndicator); // Insert before typing indicator
    userInput.value = '';
    scrollToBottom();
}

// Add Bot Message with Typing Animation logic
function addBotMessage(text, options = []) {
    // 1. Show Typing Indicator
    typingIndicator.classList.remove('hidden');
    chatBody.appendChild(typingIndicator);
    scrollToBottom();

    // 2. Hide Indicator and Show Message after delay
    setTimeout(() => {
        typingIndicator.classList.add('hidden');

        const msgDiv = document.createElement('div');
        msgDiv.className = 'message bot-msg';
        msgDiv.innerHTML = text;

        // Insert message BEFORE the typing indicator logic
        chatBody.insertBefore(msgDiv, typingIndicator);

        // Add Options Buttons if any
        if (options.length > 0) {
            const btnDiv = document.createElement('div');
            btnDiv.className = 'options-container'; // Controlled by external CSS

            options.forEach(opt => {
                const btn = document.createElement('button');
                btn.className = 'option-btn'; // Controlled by external CSS
                btn.innerText = opt;
                btn.onclick = () => handleOptionClick(opt);
                btnDiv.appendChild(btn);
            });
            chatBody.insertBefore(btnDiv, typingIndicator);
        }

        scrollToBottom();
    }, 800);
}

function handleOptionClick(text) {
    addUserMessage(text);
    processFlow(text);
}

function scrollToBottom() {
    chatBody.scrollTop = chatBody.scrollHeight;
}

/* =========================================
   3. CORE LOGIC (STATE MACHINE)
   ========================================= */
function processFlow(text) {
    // Step 1: Name Capture
    if (state.step === 1) {
        state.name = text;
        state.step = 2;
        addBotMessage(`Hi ${state.name}, please choose your language.`, ['English', 'Hindi']);
        return;
    }

    // Step 2: Language Selection
    if (state.step === 2) {
        if (text.toLowerCase().includes('hindi')) state.language = 'hi';
        else state.language = 'en';

        state.step = 3;
        const msg = state.language === 'hi' ? 'कृपया सेवा चुनें:' : 'Please select a service:';
        const opts = state.language === 'hi'
            ? ['पूछताछ (Query)', 'बुकिंग (Booking)', 'शिकायत (Complaint)', 'धनवापसी (Refund)']
            : ['Query', 'Booking', 'Complaint', 'Refund'];
        addBotMessage(msg, opts);
        return;
    }

    // Step 3: Intent Handling
    if (state.step === 3) {
        if (text.includes('Booking') || text.includes('बुकिंग')) state.intent = 'Booking';
        else if (text.includes('Complaint') || text.includes('शिकायत')) state.intent = 'Complaint';
        else if (text.includes('Refund') || text.includes('धनवापसी')) state.intent = 'Refund';
        else state.intent = 'Query';

        if (state.intent === 'Refund') {
            state.step = 90;
            addBotMessage("Please select the service date (YYYY-MM-DD):");
            userInput.type = 'date';
            userInput.focus();
        } else if (state.intent === 'Query') {
            state.step = 100; // AI Mode
            addBotMessage("Please ask your question regarding our services.");
        } else {
            state.step = 4;
            const catOpts = state.language === 'hi' ? services.hi : services.en;
            addBotMessage(state.language === 'hi' ? 'श्रेणी चुनें:' : 'Select Category:', catOpts);
        }
        return;
    }

    // Refund Logic (Step 90)
    if (state.step === 90) {
        const dateStr = text;
        const selectedDate = new Date(dateStr);
        const today = new Date();
        const diffTime = Math.abs(today - selectedDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        if (diffDays > 7) {
            addBotMessage("Sorry, you are outside the 7-day refund policy period.");
            endChat("blocked");
        } else {
            addBotMessage("Your refund request is valid. An agent will contact you shortly.");
            endChat("success");
        }
        userInput.type = 'text'; // Reset input
        return;
    }

    // AI Query Logic (Step 100)
    if (state.step === 100) {
        callGeminiAPI(text);
        return;
    }

    // Step 4: Category Selection
    if (state.step === 4) {
        state.category = text;
        state.step = 5;
        let lookupKey = text;
        const subs = subCategories[lookupKey] || ['General Support'];
        addBotMessage("Please select a specific service:", subs);
        return;
    }

    // Step 5: Sub-Category Selection
    if (state.step === 5) {
        state.subCategory = text;
        state.step = 6;
        addBotMessage("Select your City:", ['Darbhanga', 'Madhubani', 'Samastipur', 'Other']);
        return;
    }

    // Step 6: City Selection
    if (state.step === 6) {
        if (text === 'Other' || text === 'NOT available') {
            addBotMessage("Sorry, our services are not available in your location yet.");
            endChat("ended");
        } else {
            state.city = text;
            state.step = 7;
            addBotMessage("Please enter your complete address:");
        }
        return;
    }

    // Step 7: Address Capture
    if (state.step === 7) {
        state.address = text;
        state.step = 8;
        addBotMessage("Please enter your 10-digit Phone Number:");
        userInput.value = "+91 ";
        return;
    }

    // Step 8: Phone Validation & Submission
    if (state.step === 8) {
        let phone = text.replace('+91', '').trim();
        if (phone.length !== 10 || isNaN(phone)) {
            addBotMessage("Invalid number. Please enter exactly 10 digits.");
            userInput.value = "+91 ";
            return;
        }
        state.phone = "+91" + phone;

        if (state.intent === 'Booking') {
            processBooking();
        } else if (state.intent === 'Complaint') {
            addBotMessage("Complaint registered. Please call our support line: +91-12345-67890.");
            endChat("success");
        }
    }
}

/* =========================================
   4. BACKEND INTEGRATION
   ========================================= */

function processBooking() {
    // 1. Notify User
    addBotMessage("Processing your booking...");

    // 3. Prepare Data
    const formData = new FormData();
    formData.append('data', JSON.stringify(state));

    // 4. Send to Backend
    fetch('booking_backend.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        // Check if the network request itself failed (e.g., 404 file not found)
        if (!response.ok) {
            throw new Error(`Network response was not ok (Status: ${response.status})`);
        }
        return response.text();
    })
    .then(serverResponse => {
        // 5. Debugging: Log exactly what the PHP file printed
        console.log("Server Response:", serverResponse);

        // 6. Handle Response
        if (serverResponse.trim() === "Success") {
            addBotMessage("✅ Booking Successful! Status: Pending.");
            addBotMessage("Would you like a receipt?", ["Print Receipt", "No Thanks"]);
        } else {
            // If it's not "Success", it's an error message from your PHP
            addBotMessage("❌ Booking Failed. Server said:");
            addBotMessage(serverResponse); // Show the specific error to the user
        }
    })
    .catch(err => {
        // 7. Handle Network Errors (e.g., file not found, server down)
        console.error("Fetch Error:", err);
        addBotMessage("⚠️ Network Error: Could not connect to the server.");
        addBotMessage("Please check if 'booking_backend.php' exists and is in the correct folder.");
    });
}

function callGeminiAPI(query) {
    // Show typing manually for API
    typingIndicator.classList.remove('hidden');
    chatBody.appendChild(typingIndicator);
    scrollToBottom();

    const formData = new FormData();
    formData.append('message', query);

    fetch('chat_backend.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.text())
        .then(data => {
            typingIndicator.classList.add('hidden'); // Hide typing

            const msgDiv = document.createElement('div');
            msgDiv.className = 'message bot-msg';
            msgDiv.innerHTML = data;
            chatBody.insertBefore(msgDiv, typingIndicator);
            scrollToBottom();
        })
        .catch(err => {
            typingIndicator.classList.add('hidden');
            addBotMessage("Sorry, I am having trouble connecting to the server.");
        });
}

function printReceipt() {
    const content = `
        <div style="font-family: Arial; padding: 20px;">
            <h2>Abhicares Booking Receipt</h2>
            <hr>
            <p><strong>Name:</strong> ${state.name}</p>
            <p><strong>Service:</strong> ${state.category} - ${state.subCategory}</p>
            <p><strong>City:</strong> ${state.city}</p>
            <p><strong>Phone:</strong> ${state.phone}</p>
        </div>
    `;
    const w = window.open();
    w.document.write(content);
    w.print();
    w.close();
}

function endChat(status) {
    if (status === "blocked") {
        userInput.disabled = true;
        userInput.placeholder = "Chat Ended";
    } else {
        addBotMessage("Chat has ended. Thank you!");
        userInput.disabled = true;
        userInput.placeholder = "Chat Ended";
    }
}