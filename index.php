<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Redium - Dijital Pazar</title>

<style>

* {
    box-sizing: border-box;
}

body {

    margin: 0;

    background: #080a10;

    color: #fff;

    font-family:
        Arial,
        Helvetica,
        sans-serif;
}

.container {

    width: min(1180px, 92%);

    margin: auto;
}

/* NAVBAR */

.navbar {

    position: sticky;

    top: 0;

    z-index: 100;

    background: #0c0f16;

    border-bottom: 1px solid #252b38;
}

.nav {

    min-height: 70px;

    display: flex;

    align-items: center;

    gap: 20px;
}

.logo {

    font-size: 24px;

    font-weight: 900;

    white-space: nowrap;
}

.logo span {

    display: inline-grid;

    place-items: center;

    width: 38px;

    height: 38px;

    margin-right: 7px;

    border-radius: 10px;

    background: #ff304f;
}

.search {

    flex: 1;

    display: flex;
}

.search input {

    width: 100%;

    padding: 13px;

    background: #10131c;

    border: 1px solid #303747;

    color: white;

    outline: none;
}

.search button {

    border: 0;

    padding: 0 20px;

    background: #ff304f;

    color: white;

    font-weight: bold;

    cursor: pointer;
}

/* HERO */

.hero {

    padding: 90px 0;

    background:
        radial-gradient(
            circle at 70% 20%,
            #ff304f25,
            transparent 35%
        );
}

.hero h1 {

    font-size: 58px;

    margin: 0 0 20px;
}

.hero h1 span {

    color: #ff304f;
}

.hero p {

    max-width: 600px;

    color: #929aaa;

    line-height: 1.7;
}

/* BUTTON */

.btn {

    display: inline-block;

    padding: 13px 20px;

    border-radius: 9px;

    background: #ff304f;

    color: white;

    text-decoration: none;

    border: 0;

    cursor: pointer;

    font-weight: bold;
}

/* SECTIONS */

.section {

    padding: 45px 0;
}

.section h2 {

    margin-bottom: 25px;
}

/* CATEGORIES */

.categories {

    display: flex;

    gap: 10px;

    flex-wrap: wrap;
}

.category {

    padding: 11px 18px;

    background: #10131c;

    border: 1px solid #292f3d;

    color: white;

    border-radius: 9px;

    cursor: pointer;
}

.category.active {

    background: #ff304f;

    border-color: #ff304f;
}

/* PRODUCTS */

.products {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 18px;
}

.product {

    background: #10131c;

    border: 1px solid #252b38;

    border-radius: 14px;

    overflow: hidden;

    transition: .2s;
}

.product:hover {

    transform: translateY(-5px);

    border-color: #ff304f;
}

.product-image {

    height: 190px;

    background: #171b25;

    display: grid;

    place-items: center;
}

.product-image img {

    width: 100%;

    height: 100%;

    object-fit: cover;
}

.no-image {

    font-size: 50px;
}

.product-body {

    padding: 17px;
}

.product-category {

    color: #ff536b;

    font-size: 12px;

    font-weight: bold;
}

.product-body h3 {

    margin: 8px 0;
}

.description {

    color: #8f97a8;

    height: 40px;

    overflow: hidden;

    font-size: 14px;
}

.product-bottom {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-top: 18px;
}

.price {

    font-size: 18px;

    font-weight: bold;
}

.buy {

    border: 0;

    border-radius: 8px;

    padding: 9px 13px;

    background: #ff304f;

    color: white;

    cursor: pointer;
}

/* MOBILE */

@media(max-width: 900px) {

    .products {

        grid-template-columns:
            repeat(2, 1fr);
    }

}

@media(max-width: 600px) {

    .nav {

        flex-wrap: wrap;

        padding: 10px 0;
    }

    .search {

        flex-basis: 100%;
    }

    .products {

        grid-template-columns: 1fr;
    }

    .hero h1 {

        font-size: 40px;
    }

}

</style>

</head>

<body>

<header class="navbar">

<div class="container nav">

<div class="logo">

<span>R</span>

Redium

</div>

<div class="search">

<input
    id="search"
    type="text"
    placeholder="Ürün ara..."
>

<button onclick="renderProducts()">
Ara
</button>

</div>

</div>

</header>


<section class="hero">

<div class="container">

<h1>

Dijital ürünlerin

<br>

<span>yeni adresi.</span>

</h1>

<p>

Oyunlar, kodlar, hesaplar ve dijital
ürünleri Redium üzerinden keşfet.

</p>

<a
    href="#products"
    class="btn"
>
Ürünlere Git
</a>

</div>

</section>


<section class="container section">

<h2>Kategoriler</h2>

<div
    class="categories"
    id="categories"
></div>

</section>


<section
    class="container section"
    id="products"
>

<h2>Ürünler</h2>

<div
    class="products"
    id="productGrid"
></div>

</section>


<script>

let products = [];

let selectedCategory = "Tümü";


async function loadProducts() {

    try {

        const response =
            await fetch("api/products.php");

        products =
            await response.json();

        createCategories();

        renderProducts();

    } catch (error) {

        document.getElementById(
            "productGrid"
        ).innerHTML =
            "<p>Ürünler yüklenemedi.</p>";

    }

}


function createCategories() {

    const categories = [

        "Tümü",

        ...new Set(
            products.map(
                product => product.category
            )
        )

    ];

    document.getElementById(
        "categories"
    ).innerHTML =

        categories.map(category => `

            <button
                class="category
                ${category === selectedCategory
                    ? "active"
                    : ""}"

                onclick="
                    selectedCategory =
                    '${escapeHTML(category)}';

                    createCategories();

                    renderProducts();
                "
            >

                ${escapeHTML(category)}

            </button>

        `).join("");

}


function renderProducts() {

    const search =
        document
            .getElementById("search")
            .value
            .toLowerCase();

    const filtered =
        products.filter(product => {

            const text = `

                ${product.title}

                ${product.description}

                ${product.category}

            `.toLowerCase();

            return (

                (
                    selectedCategory === "Tümü" ||
                    product.category === selectedCategory
                )

                &&

                text.includes(search)

            );

        });


    const grid =
        document.getElementById(
            "productGrid"
        );


    if (filtered.length === 0) {

        grid.innerHTML =
            "<p>Ürün bulunamadı.</p>";

        return;

    }


    grid.innerHTML = filtered.map(product => `

        <article class="product">

            <div class="product-image">

                ${
                    product.image

                    ?

                    `
                    <img
                        src="${escapeHTML(product.image)}"
                        alt="${escapeHTML(product.title)}"
                    >
                    `

                    :

                    `
                    <div class="no-image">
                        🛍️
                    </div>
                    `
                }

            </div>


            <div class="product-body">

                <div class="product-category">

                    ${escapeHTML(product.category)}

                </div>


                <h3>

                    ${escapeHTML(product.title)}

                </h3>


                <div class="description">

                    ${escapeHTML(product.description)}

                </div>


                <div class="product-bottom">

                    <div class="price">

                        ${Number(product.price)
                            .toLocaleString(
                                "tr-TR",
                                {
                                    style: "currency",
                                    currency: "TRY"
                                }
                            )}

                    </div>


                    <button
                        class="buy"
                        onclick="addToCart(${product.id})"
                    >

                        Sepete

                    </button>

                </div>

            </div>

        </article>

    `).join("");

}


function escapeHTML(text) {

    return String(text)
        .replace(
            /[&<>"']/g,
            character => ({

                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#039;"

            })[character]
        );

}


function addToCart(id) {

    alert(
        "Ürün sepete eklendi! Ürün ID: " + id
    );

}


document
    .getElementById("search")
    .addEventListener(
        "input",
        renderProducts
    );


loadProducts();

</script>

</body>

</html>