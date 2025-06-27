<template>
    <div style="display: grid">
        <a
            class="dropdown-toggle no-arrow text-secondary fs-12 ml-xl-4 load-articles hov-bg-black-10"
            @click="toggleDropdown"
            style="border-radius: 10px 10px 0 0"
            role="button"
            aria-haspopup="false"
            aria-expanded="false"
        >
            <span>
                <span class="position-relative d-inline-block text-secondary">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        height="20"
                        width="22.5"
                        viewBox="0 0 576 512"
                    >
                        <path
                            d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"
                        />
                    </svg>
                </span>
            </span>
        </a>

        <div
            v-if="isDropdownVisible"
            class="py-0 rounded-0 articles-container"
            :class="{ show: isDropdownVisible }"
        >
            <div class="p-3 bg-light border-bottom">
                <h6 class="mb-0">Tus piezas</h6>
            </div>
            <div
                class="px-3 c-scrollbar-light overflow-auto"
                style="max-height: 300px"
            >
                <ul class="list-group list-group-flush" id="articles-container">
                    <li
                        v-for="article in articles"
                        :key="article.id"
                        class="list-group-item d-flex justify-content-between lh-condensed my-1 hover-article"
                    >
                        <span>
                            <img
                                class="cat-image lazyload mr-2 opacity-60 icon-size"
                                :src="article.category_icon_url"
                                :alt="article.category_name"
                                width="16"
                            />
                        </span>
                        <div class="w-75">
                            <a
                                href="javascript:void(0)"
                                @click="loadNewArticle(article.category_id)"
                                class="hov-bg-black-10"
                            >
                                <span
                                    class="text-muted d-block w-100 mb-0 mt-1"
                                    >{{
                                        formatProductName(article.product_name)
                                    }}</span
                                >
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
            <div
                class="text-center border-top d-flex px-2 justify-content-between align-items-center mt-2"
            >
                <a href="/articles" class="text-secondary fs-12 d-block py-2">
                    Ir a mis artículos
                </a>
            </div>
        </div>

        <div
            v-if="loading"
            id="preloader"
            style="display: flex; justify-content: center; height: 50px"
        >
            <img
                src="/lapieza/public/assets/css/ajax-loader.gif"
                alt="Loading..."
            />
        </div>
    </div>
</template>

<script>
export default {
    name: "ArticlesDropdown",
    data() {
        return {
            articles: [],
            loading: false,
            isDropdownVisible: false, // Estado para controlar la visibilidad del menú desplegable
        };
    },
    methods: {
        async loadArticles() {
            this.loading = true;
            try {
                const response = await fetch("/lapieza/load_articles");
                const data = await response.json();

                if (Array.isArray(data.articles)) {
                    this.articles = data.articles;
                } else {
                    console.error(
                        "La respuesta no contiene el array esperado:",
                        data,
                    );
                    this.articles = [];
                }
            } catch (error) {
                console.error("Error al cargar artículos:", error);
                this.articles = [];
            } finally {
                this.loading = false;
            }
        },
        toggleDropdown() {
            this.isDropdownVisible = !this.isDropdownVisible; // Cambia la visibilidad al hacer clic
            if (this.isDropdownVisible) {
                this.loadArticles(); // Solo carga artículos si el dropdown está visible
            }
        },
        async loadNewArticle(articleId) {
            this.loading = true;

            try {
                const response = await fetch("/get-articles-by-id", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify({
                        article_id: articleId,
                        user_id: 1, // Sustituir por el ID del usuario autenticado
                    }),
                });

                const data = await response.json();
                if (data) {
                    this.$emit("update-articles", data.html);
                } else {
                    console.log("No se recibió ningún dato para el artículo.");
                }
            } catch (error) {
                console.error("Error al cargar el artículo:", error);
            } finally {
                this.loading = false;
            }
        },
        formatProductName(name) {
            if (name === name.toUpperCase()) {
                return this.capitalizeWords(name.toLowerCase());
            }
            return name;
        },
        capitalizeWords(str) {
            return str.replace(/\b\w/g, (char) => char.toUpperCase());
        },
    },
};
</script>

<style scoped>
.articles-container {
    background-color: white;
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 0.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: absolute;
    z-index: 1000;
    width: 300px;
    max-height: 400px;
    overflow-y: auto;
    transition:
        opacity 0.3s ease,
        transform 0.3s ease;
    opacity: 0;
    transform: translateY(-10px);
}

.articles-container.show {
    opacity: 1;
    transform: translateY(0);
}

.hover-article:hover {
    background-color: rgba(0, 0, 0, 0.1);
    border-radius: 0.5rem;
    transition: background-color 0.3s ease;
}
</style>
