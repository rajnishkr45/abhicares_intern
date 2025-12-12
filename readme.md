# 💬 AbhiCares Dummy 

This project is a **simple and beautiful chatbot interface** built using **HTML, CSS, JavaScript, and PHP**.
No message is sent to the backend initially — the bot replies using a **dummy array of predefined responses**.

---

## 🚀 Features

* 🌟 **Modern & smooth chatbot UI**
* 💬 **Bot replies from a dummy array** (no AI or external API required)
* ⚡ **Lightweight PHP backend**
* 📱 **Fully responsive design**
* 🎨 **Clean animations & message bubbles**
* 🧩 Easy to customize and integrate into any project

---

## 📂 Project Structure

```
/chatbot
│── index.php          # Chatbot frontend UI + JS
│── chatbot.php        # PHP backend with dummy responses
│── assets/
│     ├── style.css    # Modern chatbot design
│     └── script.js    # Frontend message handling
```

---

## 🛠️ How It Works

### 1️⃣ User sends a message

The frontend captures the message and sends it to `chatbot.php` using AJAX.

### 2️⃣ Bot generates a reply

`chatbot.php` contains a **dummy array** like:

```php
$bot_responses = [
   "hi" => "Hello! How can I assist you today?",
   "hello" => "Hi there! What's up?",
   "help" => "Sure! Tell me what help you need."
];
```

If no match is found → bot replies with a default generic message.

### 3️⃣ Response shown in UI

Smooth UI appends the bot’s reply using message bubbles with typing animation and auto-scroll.

---

## ⚙️ Setup Instructions

### ✔ Step 1: Download or clone the project

```bash
git clone https://github.com/rajnishkr45/abhicares_inter.git
```

### ✔ Step 2: Move to your localhost server

If using **XAMPP**:

```
htdocs/chatbot/
```

If using **WAMP**:

```
www/chatbot/
```

### ✔ Step 3: Run project

Open in browser:

```
http://localhost/chatbot/
```

---

## 🎨 Customize the Bot Responses

Open `chatbot.php` and edit or add responses:

```php
$bot_responses["your keyword"] = "your custom reply";
```

Example:

```php
$bot_responses["college"] = "Your college chatbot is ready!";
```

---

## 🧪 Frontend Preview

* Smooth UI
* Chat bubbles
* Typing animation
* Scroll-to-bottom feature

You can change the theme inside `assets/style.css`.

---

## 🧰 Technologies Used

| Technology     | Purpose                |
| -------------- | ---------------------- |
| **HTML**       | Structure              |
| **CSS**        | Styling, animations    |
| **JavaScript** | Message handling, AJAX |
| **PHP**        | Backend reply system   |

---

## 🧩 Future Improvements

* Add OpenAI or other LLM API integration
* Add memory-based conversation (session or DB)
* Add admin panel to edit responses dynamically
* Store chat logs in a database for analytics

---

