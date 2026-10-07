/* ==========================================
   REDIUM
   Dijital ürün pazaryeri demo
========================================== */


const products = [

    {
        id: 1,
        name: "Valorant VP Paketi",
        cat: "Kod",
        icon: "🎯",
        price: 249.90,
        desc: "Hızlı teslimat • Dijital kod"
    },

    {
        id: 2,
        name: "Minecraft Premium",
        cat: "Oyun",
        icon: "⛏️",
        price: 399.90,
        desc: "Java & Bedrock • Dijital ürün"
    },

    {
        id: 3,
        name: "Steam Cüzdan Kodu",
        cat: "Kod",
        icon: "🎮",
        price: 179.90,
        desc: "Anında teslim • Güvenli ödeme"
    },

    {
        id: 4,
        name: "Gaming Hesap",
        cat: "Hesap",
        icon: "👤",
        price: 299.90,
        desc: "Çeşitli oyunlar • Hızlı teslim"
    },

    {
        id: 5,
        name: "Rare Game Item",
        cat: "Eşya",
        icon: "💎",
        price: 129.90,
        desc: "Oyun içi eşya • Hızlı teslim"
    },

    {
        id: 6,
        name: "Roblox Robux",
        cat: "Kod",
        icon: "🟥",
        price: 219.90,
        desc: "Dijital kod • Anında teslim"
    },

    {
        id: 7,
        name: "Discord Nitro",
        cat: "Kod",
        icon: "💬",
        price: 149.90,
        desc: "Dijital üyelik • Kolay aktivasyon"
    },

    {
        id: 8,
        name: "Epic Games Ürünü",
        cat: "Oyun",
        icon: "⚡",
        price: 329.90,
        desc: "Dijital ürün • Güvenli alışveriş"
    }

];


/* ==========================================
   DEĞİŞKENLER
========================================== */


let cart =
    JSON.parse(
        localStorage.getItem("rediumCart") || "[]"
    );


let currentCategory = "Tümü";


let authMode = "login";


let currentUser =
    sessionStorage.getItem("rediumUser") || "";


/* ==========================================
   KISA ID FONKSİYONU
========================================== */


function $(id) {
    return document.getElementById(id);
}


/* ==========================================
   PARA FORMAT
========================================== */


function money(value) {

    return value.toLocaleString(
        "tr-TR",
        {
            style: "currency",
            currency: "TRY"
        }
    );

}


/* ==========================================
   SEPETİ KAYDET
========================================== */


function saveCart() {

    localStorage.setItem(
        "rediumCart",
        JSON.stringify(cart)
    );

    updateCartCount();

}


/* ==========================================
   SEPET SAYISI
========================================== */


function updateCartCount() {

    const count =
        cart.reduce(
            (total, item) =>
                total + item.quantity,
            0
        );


    $("cartCount").textContent = count;

}


/* ==========================================
   ÜRÜNLERİ GÖSTER
========================================== */


function render() {

    const search =
        $("searchInput")
            .value
            .trim()
            .toLowerCase();


    const list =
        products.filter(product => {

            const categoryMatch =
                currentCategory === "Tümü" ||
                product.cat === currentCategory;


            const searchMatch =
                !search ||

                `${product.name}
                ${product.cat}
                ${product.desc}`
                    .toLowerCase()
                    .includes(search);


            return categoryMatch &&
                searchMatch;

        });


    $("resultInfo").textContent =
        `${list.length} ürün`;


    if (list.length === 0) {

        $("productGrid").innerHTML = `

            <div
                style="
                    grid-column:1/-1;
                    padding:40px 0;
                    text-align:center;
                    color:#9098aa;
                "
            >

                Aramana uygun ürün bulunamadı.

            </div>

        `;

        return;
    }


    $("productGrid").innerHTML =
        list.map(product => `

            <article class="product">

                <div class="product-img">

                    <span class="tag">
                        ${product.cat}
                    </span>

                    ${product.icon}

                </div>


                <div class="product-body">

                    <h3>
                        ${product.name}
                    </h3>


                    <p>
                        ${product.desc}
                    </p>


                    <div class="price">

                        <strong>
                            ${money(product.price)}
                        </strong>


                        <button
                            class="buy"
                            data-add="${product.id}"
                        >
                            Sepete Ekle
                        </button>

                    </div>

                </div>

            </article>

        `).join("");

}


/* ==========================================
   ÜRÜNÜ SEPETE EKLE
========================================== */


$("productGrid")
    .addEventListener(
        "click",
        event => {

            const button =
                event.target.closest(
                    "[data-add]"
                );


            if (!button) return;


            addCart(
                Number(button.dataset.add)
            );

        }
    );


function addCart(id) {

    const product =
        products.find(
            item => item.id === id
        );


    if (!product) return;


    const existing =
        cart.find(
            item => item.id === id
        );


    if (existing) {

        existing.quantity++;

    } else {

        cart.push({
            id: id,
            quantity: 1
        });

    }


    saveCart();


    showToast(
        product.name +
        " sepete eklendi."
    );

}


/* ==========================================
   SEPETİ GÖSTER
========================================== */


function renderCart() {

    const container =
        $("cartItems");


    if (cart.length === 0) {

        container.innerHTML = `

            <div class="empty-cart">

                🛒

                <br><br>

                Sepetin şu an boş.

            </div>

        `;

    } else {

        container.innerHTML =
            cart.map(item => {

                const product =
                    products.find(
                        p => p.id === item.id
                    );


                if (!product) {
                    return "";
                }


                return `

                    <div class="cart-row">

                        <span class="cart-emoji">
                            ${product.icon}
                        </span>


                        <div class="cart-product">

                            <strong>
                                ${product.name}
                            </strong>

                            <small>
                                ${money(product.price)}
                                ×
                                ${item.quantity}
                            </small>

                        </div>


                        <button
                            class="remove-item"
                            data-remove="${product.id}"
                        >
                            Sil
                        </button>

                    </div>

                `;

            }).join("");

    }


    const total =
        cart.reduce(
            (sum, item) => {

                const product =
                    products.find(
                        p => p.id === item.id
                    );


                if (!product) {
                    return sum;
                }


                return sum +
                    product.price *
                    item.quantity;

            },
            0
        );


    $("cartTotal").textContent =
        money(total);

}


/* ==========================================
   SEPETTEN ÜRÜN SİL
========================================== */


$("cartItems")
    .addEventListener(
        "click",
        event => {

            const button =
                event.target.closest(
                    "[data-remove]"
                );


            if (!button) return;


            const id =
                Number(
                    button.dataset.remove
                );


            cart =
                cart.filter(
                    item =>
                        item.id !== id
                );


            saveCart();

            renderCart();


            showToast(
                "Ürün sepetten kaldırıldı."
            );

        }
    );


/* ==========================================
   SEPETİ AÇ
========================================== */


$("cartBtn")
    .addEventListener(
        "click",
        () => {

            renderCart();

            $("cartModal")
                .classList
                .remove("hidden");

        }
    );


/* ==========================================
   SEPETİ KAPAT
========================================== */


$("closeCart")
    .addEventListener(
        "click",
        () => {

            $("cartModal")
                .classList
                .add("hidden");

        }
    );


$("cartModal")
    .addEventListener(
        "click",
        event => {

            if (
                event.target ===
                $("cartModal")
            ) {

                $("cartModal")
                    .classList
                    .add("hidden");

            }

        }
    );


/* ==========================================
   GİRİŞ MODALINI AÇ
========================================== */


function openAuth(mode) {

    authMode = mode;

    updateAuthForm();


    $("modal")
        .classList
        .remove("hidden");

}


/* ==========================================
   GİRİŞ / KAYIT FORMU
========================================== */


function updateAuthForm() {

    const register =
        authMode === "register";


    $("authTitle").textContent =
        register
            ? "Yeni hesap oluştur"
            : "Hesabına giriş yap";


    $("authDescription").textContent =
        register
            ? "Redium'a katıl!"
            : "Redium hesabına hoş geldin!";


    $("authSubmit").textContent =
        register
            ? "Kaydol"
            : "Giriş Yap";


    $("showLogin")
        .classList
        .toggle(
            "active",
            !register
        );


    $("showRegister")
        .classList
        .toggle(
            "active",
            register
        );


    $("authSwitch").innerHTML =
        register

            ? `
                Zaten hesabın var mı?
                <a href="#" id="switchLink">
                    Giriş Yap
                </a>
              `

            : `
                Hesabın yok mu?
                <a href="#" id="switchLink">
                    Kaydol
                </a>
              `;

}


/* ==========================================
   GİRİŞ BUTONU
========================================== */


$("loginBtn")
    .addEventListener(
        "click",
        () => {

            if (currentUser) {

                showToast(
                    "Merhaba, " +
                    currentUser +
                    "!"
                );

            } else {

                openAuth("login");

            }

        }
    );


/* ==========================================
   AUTH TABLARI
========================================== */


$("showLogin")
    .addEventListener(
        "click",
        () => {

            openAuth("login");

        }
    );


$("showRegister")
    .addEventListener(
        "click",
        () => {

            openAuth("register");

        }
    );


/* ==========================================
   GİRİŞ/KAYIT DEĞİŞTİR
========================================== */


$("authSwitch")
    .addEventListener(
        "click",
        event => {

            if (
                event.target.id !==
                "switchLink"
            ) {
                return;
            }


            event.preventDefault();


            openAuth(
                authMode === "login"
                    ? "register"
                    : "login"
            );

        }
    );


/* ==========================================
   MODALI KAPAT
========================================== */


$("closeModal")
    .addEventListener(
        "click",
        () => {

            $("modal")
                .classList
                .add("hidden");

        }
    );


$("modal")
    .addEventListener(
        "click",
        event => {

            if (
                event.target ===
                $("modal")
            ) {

                $("modal")
                    .classList
                    .add("hidden");

            }

        }
    );


/* ==========================================
   KAYIT / GİRİŞ
========================================== */


$("authForm")
    .addEventListener(
        "submit",
        event => {

            event.preventDefault();


            const username =
                $("authUsername")
                    .value
                    .trim();


            const password =
                $("authPassword")
                    .value;


            if (
                username.length < 3
            ) {

                showToast(
                    "Kullanıcı adı en az 3 karakter olmalı."
                );

                return;

            }


            if (
                password.length < 6
            ) {

                showToast(
                    "Şifre en az 6 karakter olmalı."
                );

                return;

            }


            let users;


            try {

                users =
                    JSON.parse(
                        localStorage.getItem(
                            "rediumUsers"
                        ) || "{}"
                    );

            } catch {

                users = {};

            }


            /* KAYIT */

            if (
                authMode ===
                "register"
            ) {

                if (
                    users[username]
                ) {

                    showToast(
                        "Bu kullanıcı adı zaten kayıtlı."
                    );

                    return;

                }


                users[username] = {
                    password: password
                };


                localStorage.setItem(
                    "rediumUsers",
                    JSON.stringify(users)
                );


                currentUser =
                    username;


                localStorage.setItem(
                    "rediumUser",
                    username
                );


                $("modal")
                    .classList
                    .add("hidden");


                updateHeader();


                showToast(
                    "Kayıt başarılı! Hoş geldin, " +
                    username
                );


                return;

            }


            /* GİRİŞ */

            if (
                !users[username]
            ) {

                showToast(
                    "Bu kullanıcı bulunamadı."
                );

                return;

            }


            if (
                users[username].password !==
                password
            ) {

                showToast(
                    "Şifre yanlış."
                );

                return;

            }


            currentUser =
                username;


            localStorage.setItem(
                "rediumUser",
                username
            );


            $("modal")
                .classList
                .add("hidden");


            updateHeader();


            showToast(
                "Giriş başarılı! Hoş geldin, " +
                username
            );

        }
    );


/* ==========================================
   HEADER KULLANICI DURUMU
========================================== */


function updateHeader() {

    const button =
        $("loginBtn");


    if (currentUser) {

        button.textContent =
            "Çıkış Yap";


        button.classList.add(
            "logout-btn"
        );


        button.onclick =
            logout;

    } else {

        button.textContent =
            "Giriş Yap";


        button.classList.remove(
            "logout-btn"
        );


        button.onclick =
            () => openAuth("login");

    }

}


/* ==========================================
   ÇIKIŞ
========================================== */


function logout() {

    currentUser = "";

    localStorage.removeItem(
        "rediumUser"
    );


    updateHeader();


    showToast(
        "Başarıyla çıkış yaptın."
    );

}


/* ==========================================
   SATIN ALMA
========================================== */


$("checkoutBtn")
    .addEventListener(
        "click",
        () => {

            if (!currentUser) {

                $("cartModal")
                    .classList
                    .add("hidden");


                openAuth("login");


                showToast(
                    "Satın almak için önce giriş yap."
                );


                return;

            }


            if (cart.length === 0) {

                showToast(
                    "Sepetin boş."
                );

                return;

            }


            showToast(
                "Demo ödeme sistemi hazır değil."
            );

        }
    );


/* ==========================================
   ARAMA
========================================== */


$("searchBtn")
    .addEventListener(
        "click",
        render
    );


$("searchInput")
    .addEventListener(
        "input",
        render
    );


/* ==========================================
   KATEGORİLER
========================================== */


document
    .querySelectorAll(".category")
    .forEach(button => {

        button.addEventListener(
            "click",
            () => {

                document
                    .querySelectorAll(".category")
                    .forEach(
                        item =>
                            item.classList
                                .remove("active")
                    );


                button.classList.add(
                    "active"
                );


                currentCategory =
                    button.dataset.category;


                render();

            }
        );

    });


/* ==========================================
   BİLDİRİM
========================================== */


let toastTimer;


function showToast(message) {

    const toast =
        $("toast");


    toast.textContent =
        message;


    toast.classList.add(
        "show"
    );


    clearTimeout(
        toastTimer
    );


    toastTimer =
        setTimeout(
            () => {

                toast.classList.remove(
                    "show"
                );

            },
            2500
        );

}


/* ==========================================
   BAŞLANGIÇ
========================================== */


currentUser =
    localStorage.getItem(
        "rediumUser"
    ) || "";


updateCartCount();

updateHeader();

render();