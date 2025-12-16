<?php

// Define the Categories (Tags), the Standard Reply, and a list of User Patterns
$dataset = [
    "Greetings" => [
        "reply" => "I am AbhiCares virtual assistant. How can I help you with your AC or appliance today?",
        "patterns" => [
            "Hi", "Hello", "Hey", "Good morning", "Good evening", "Good afternoon", "Yo", 
            "Are you there?", "Is anyone there?", "Hi bot", "Hello support", "Greetings", 
            "Start", "Wake up", "Help me", "I need help", "Can you assist me?", 
            "Are you a real person?", "Who are you?", "What is this?", "Hi AbhiCares", 
            "Heya", "Hi there", "Hello friend", "Anyone home?", "Support please"
        ]
    ],
    "Issue_Cooling" => [
        "reply" => "It sounds like a cooling issue. Please switch off the AC immediately to prevent compressor damage and book a repair service.",
        "patterns" => [
            "AC not working", "AC not cooling", "Air conditioner is warm", "Blowing hot air", 
            "My AC isn't cold enough", "Room is not getting cold", "Temperature not dropping", 
            "AC fan running but no cooling", "Compressor not starting", "The air is humid", 
            "Why is my AC blowing hot air?", "Cooling is very slow", "It takes hours to cool", 
            "AC stopped cooling suddenly", "No cool air", "Warm air coming out", 
            "AC vent blowing dust", "Thermostat not working", "AC is useless", 
            "I am sweating, AC not working", "Fix my cooling", "Not chilling", 
            "Gas leakage maybe?", "Need gas refill", "Check AC gas", "Low cooling", 
            "Performance is bad", "AC is on but room is hot", "Just blowing air", 
            "Fan is working but compressor is silent"
        ]
    ],
    "Issue_Hardware" => [
        "reply" => "For hardware issues like leaks or noise, please unplug the device. A technician needs to inspect the unit.",
        "patterns" => [
            "AC leaking water", "Water dripping from AC", "Water leakage inside room", 
            "AC making weird noise", "Loud buzzing sound", "AC is rattling", 
            "Grinding noise from AC", "Water falls on my bed", "Leaking unit", 
            "AC smells bad", "Burning smell from AC", "Smoke coming from AC", 
            "Sparks in AC switch", "Remote not working", "Display is off", 
            "Cannot turn on AC", "Swing blade broken", "Filter is choked", 
            "AC is freezing up", "Ice formation on AC", "Ice throwing", 
            "Water drop problem", "Heavy vibration", "Outdoor unit making noise", 
            "Indoor unit dripping", "Bad smell when I turn it on", "Foul odor", 
            "Remote display broken", "Cannot change temperature", "Buttons stuck"
        ]
    ],
    "Service_Booking" => [
        "reply" => "You can book a service instantly through our app or by replying with your address and preferred time.",
        "patterns" => [
            "Book service", "I need a repair", "Schedule a technician", "Send someone to fix it", 
            "I want to book a complaint", "Register a complaint", "Technician needed", 
            "Mechanic for AC", "Book appointment", "When can you come?", "Urgent repair", 
            "Book a slot", "Reserve a service", "Need AC service", "Dry service needed", 
            "Wet service needed", "Deep cleaning required", "Installation service", 
            "Uninstall my AC", "Relocate my AC", "Fitting required", "Installation cost?", 
            "I want to install new AC", "Schedule a visit", "Book tomorrow", 
            "Book for today", "Emergency service", "Come now", "Fix it today", 
            "Technician visit request", "Service request", "Maintenance needed"
        ]
    ],
    "Service_Status" => [
        "reply" => "You can track your service status on the dashboard. Please provide your Order ID if you want me to check.",
        "patterns" => [
            "Service status", "Where is the technician?", "Track my order", "Technician not arrived", 
            "Is he coming?", "Late service", "Check my booking", "Booking status", 
            "When will he reach?", "ETA of technician", "Nobody came yet", "Delay in service", 
            "Why is it taking so long?", "Status of complaint", "Complaint ID status", 
            "Track request", "Where is my mechanic?", "Has he left?", "Contact technician", 
            "Give me technician number", "Technician location", "Update on service", 
            "Is my appointment confirmed?", "Did you forget me?", "Waiting for service"
        ]
    ],
    "Service_Cancel" => [
        "reply" => "We are sorry to see you go. To cancel, please provide your Booking ID. Note that cancellation charges may apply if the technician has already left.",
        "patterns" => [
            "Cancel service", "Cancel my booking", "I don't need help anymore", "Cancel appointment", 
            "Stop the technician", "Reschedule instead", "Change time", "Change date", 
            "I am not at home", "Cancel request", "Delete booking", "Remove complaint", 
            "Technician refused to come, cancel it", "Too expensive, cancel", 
            "Found another mechanic, cancel", "Abort service", "Don't come", 
            "Wrong booking", "Mistake booking", "Cancel please"
        ]
    ],
    "Payment_Cost" => [
        "reply" => "Our visiting charge is $20. Repair costs depend on the spare parts required. You can pay via Cash, Card, or UPI.",
        "patterns" => [
            "How much does it cost?", "What is the price?", "Service charge?", "Visit fee", 
            "Is it free?", "Repair cost", "Price list", "How much for gas refill?", 
            "Cost of installation", "Hidden charges?", "Do I pay now?", "Payment options", 
            "Do you accept credit card?", "Can I pay cash?", "UPI available?", 
            "Gpay or PhonePe?", "Is it expensive?", "Discount available?", "Coupon code?", 
            "Refund policy", "Money back guarantee?", "Quote for repair", "Estimate price", 
            "Payment failed", "Transaction failed", "I paid but status is pending", 
            "Double deduction", "Wrong amount charged", "Bill is high", "Overcharged"
        ]
    ],
    "Contact_Support" => [
        "reply" => "You can reach our human support team at 1800-ABHI-CARE or email support@abhicares.com.",
        "patterns" => [
            "Call support", "Customer care number", "Phone number", "Mobile number", 
            "Talk to human", "Talk to agent", "I want to speak to a person", "Connect me to staff", 
            "Chat with human", "Email support", "Email address", "Where is your office?", 
            "Head office", "Complaint number", "Escalate issue", "Manager number", 
            "Supervisor please", "Toll free number", "Helpline", "Contact info", 
            "How to reach you?", "Give me a call", "Call me back", "Callback request"
        ]
    ],
    "Locations" => [
        "reply" => "We currently serve Patna, Delhi, Mumbai, and Bangalore. Are you located in one of these cities?",
        "patterns" => [
            "Where do you serve?", "Service area", "Do you come to Patna?", "Available in Delhi?", 
            "My location is Mumbai", "Are you in my city?", "Pincode availability", 
            "Do you service rural areas?", "Coverage area", "List of cities", 
            "Is service available here?", "My address is...", "Do you cover Bangalore?", 
            "Remote area service", "How far do you travel?"
        ]
    ],
     "Warranty" => [
        "reply" => "We offer a 30-day warranty on all services and a 90-day warranty on spare parts.",
        "patterns" => [
            "Is there a warranty?", "Guarantee period", "Service warranty", "Parts warranty", 
            "What if it breaks again?", "Do you provide bill?", "Invoice needed", 
            "Warranty claim", "It broke again", "Same problem again", "Warranty expired", 
            "Is gas refill covered?", "Recall policy", "Terms and conditions"
        ]
    ]
];

// Open file
$file = fopen("abhicares_knowledge.csv", "w");

// Write Header
fputcsv($file, ["tag", "pattern", "response"]);

$count = 0;

// Iterate through the structured dataset
foreach ($dataset as $tag => $data) {
    $reply = $data['reply'];
    $patterns = $data['patterns'];
    
    foreach ($patterns as $pattern) {
        fputcsv($file, [
            $tag,
            $pattern,
            $reply
        ]);
        $count++;
    }
}

fclose($file);

echo "<h3>Success!</h3>";
echo "<p>CSV generated with <strong>$count</strong> unique training rows.</p>";
echo "<p>File saved as: <code>abhicares_knowledge.csv</code></p>";

// Optional: Display a preview of the first few rows
echo "<pre>";
echo "<strong>Preview of Data:</strong>\n";
$preview = array_slice($dataset, 0, 2); 
print_r($preview); 
echo "</pre>";

?>