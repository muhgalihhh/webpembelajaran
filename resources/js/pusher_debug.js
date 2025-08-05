// Paste script debug ini di browser console

console.clear();
console.log("🚀 Enhanced Broadcasting Debug Started");

// 1. Cek authentication status
async function checkAuth() {
    console.group("🔑 Authentication Status");
    try {
        // Coba beberapa endpoint auth yang mungkin ada
        const endpoints = ["/api/user", "/user", "/sanctum/csrf-cookie"];

        for (const endpoint of endpoints) {
            try {
                const response = await fetch(endpoint, {
                    headers: {
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    credentials: "same-origin",
                });
                console.log(`Checking ${endpoint}...`);
                if (response.ok) {
                    const data = await response.json();
                    console.log(`✅ ${endpoint} - Success:`, data);
                    if (data.class_id) {
                        window.currentClassId = data.class_id;
                        console.log(`📚 User class ID: ${data.class_id}`);
                    }
                    break;
                } else {
                    console.log(`❌ ${endpoint} - Failed: ${response.status}`);
                }
            } catch (e) {
                console.log(`❌ ${endpoint} - Error:`, e.message);
            }
        }
    } catch (error) {
        console.error("Auth check failed:", error);
    }
    console.groupEnd();
}

// 2. Cek CSRF token
function checkCSRF() {
    console.group("🛡️ CSRF Token");
    const token = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");
    console.log("CSRF Token:", token ? "✅ Found" : "❌ Missing");
    if (!token) {
        console.warn(
            'Add this to your layout head: <meta name="csrf-token" content="{{ csrf_token() }}">'
        );
    }
    console.groupEnd();
}

// 3. Test Pusher connection dengan retry
function testPusherConnection() {
    console.group("📡 Pusher Connection Test");

    if (!window.Echo) {
        console.error("❌ Laravel Echo not found");
        console.groupEnd();
        return false;
    }

    const pusher = window.Echo.connector.pusher;
    console.log("Connection state:", pusher.connection.state);

    // Force connection
    if (pusher.connection.state !== "connected") {
        console.log("🔄 Attempting to connect...");
        pusher.connect();
    }

    // Listen for connection events
    pusher.connection.bind("connected", () => {
        console.log(
            "✅ Pusher connected! Socket ID:",
            pusher.connection.socket_id
        );
    });

    pusher.connection.bind("error", (error) => {
        console.error("❌ Pusher connection error:", error);
    });

    pusher.connection.bind("disconnected", () => {
        console.log("⚠️ Pusher disconnected");
    });

    console.groupEnd();
    return true;
}

// 4. Test private channel subscription
function testChannelSubscription(classId) {
    console.group(`🔐 Testing Private Channel: class.${classId}`);

    if (!window.Echo) {
        console.error("❌ Echo not available");
        console.groupEnd();
        return;
    }

    // Test broadcast auth endpoint
    fetch("/broadcasting/auth", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN":
                document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content") || "",
        },
        body: JSON.stringify({
            channel_name: `private-class.${classId}`,
            socket_id: window.Echo.connector.pusher.connection.socket_id,
        }),
        credentials: "same-origin",
    })
        .then((response) => {
            console.log("Auth response status:", response.status);
            return response.json();
        })
        .then((data) => {
            console.log("Auth response:", data);
        })
        .catch((error) => {
            console.error("Auth request failed:", error);
        });

    // Subscribe to channel
    const channel = window.Echo.private(`class.${classId}`);

    channel.subscribed(() => {
        console.log(`✅ Successfully subscribed to class.${classId}`);
    });

    channel.error((error) => {
        console.error(`❌ Subscription failed for class.${classId}:`, error);
    });

    // Listen for events
    ["material.created", "task.created", "quiz.created"].forEach(
        (eventType) => {
            channel.listen(`.${eventType}`, (data) => {
                console.log(`📢 Received ${eventType}:`, data);

                // Trigger UI update
                window.dispatchEvent(new CustomEvent("livewire:refresh"));
            });
        }
    );

    console.groupEnd();
    return channel;
}

// 5. Manual notification test
function simulateNotification(classId) {
    console.group("🧪 Manual Notification Test");

    const testData = {
        notification: {
            type: "Materi Baru",
            title: "Test Material",
            subject_name: "Test Subject",
        },
        user_id: 1,
    };

    // Trigger Livewire method directly
    const notificationDropdown = document.querySelector("[wire\\:id]");
    if (notificationDropdown && window.Livewire) {
        const componentId = notificationDropdown.getAttribute("wire:id");
        const component = window.Livewire.find(componentId);

        if (component) {
            console.log("📤 Triggering handleNewNotification manually...");
            component.call("handleNewNotification", testData);
        }
    }

    console.groupEnd();
}

// 6. Run all tests
async function runAllTests() {
    await checkAuth();
    checkCSRF();

    if (testPusherConnection()) {
        // Wait for connection
        setTimeout(() => {
            if (window.currentClassId) {
                testChannelSubscription(window.currentClassId);
            } else {
                console.warn("⚠️ No class ID found, using default class ID 1");
                testChannelSubscription(1);
            }
        }, 2000);
    }
}

// Make functions globally available
window.checkAuth = checkAuth;
window.testChannelSubscription = testChannelSubscription;
window.simulateNotification = simulateNotification;

// Auto run tests
runAllTests();

console.log("\n📋 Available functions:");
console.log("- checkAuth()");
console.log("- testChannelSubscription(classId)");
console.log("- simulateNotification(classId)");
