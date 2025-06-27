@if (Auth::user() && Auth::user()->user_type == 'customer' && request()->routeIs('home'))

    {{--<a class="dropdown-toggle no-arrow text-secondary fs-12  ml-xl-4 load_articles hov-bg-black-10"
       data-toggle="dropdown"
       role="button"
       aria-expanded="false" data-title="Mis Artículos" style="border-radius: 10px 10px 0 0;" tabindex="0">


    <span class="circle-red" aria-hidden="true">
        <span class="icon plus"></span>
    </span>

    </a>--}}

    <a class="dropdown-toggle no-arrow text-secondary fs-12 ml-xl-4 load_articles hov-bg-black-10"
       data-toggle="dropdown"
       role="button"
       aria-expanded="false" data-title="Mis artículos" style="border-radius: 10px 10px 0 0;" tabindex="0">
        <span class="circle-articles">
            <span class="plus"></span>
        </span>&nbsp;
        <span class="fw-700 text-negro fs-13" id="category_text"></span>
    </a>


    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg py-0 border-bottom-2l">
        <div class="p-3 bg-light border-bottom">
            <h6 class="mb-0">Mis Artículos</h6>
        </div>
        <div class="px-3 c-scrollbar-light overflow-auto " style="max-height:300px;">

            <ul class="list-group list-group-flush" id="articles-container">
            </ul>
        </div>
        <div class="text-center border-top px-2 mt-2">
            <a href="{{ route('articles.index')  }}"
               class="fs-13 px-3 py-1 d-inline-block fw-700 text-negro header_menu_links hov-bg-black-10 hover-article">
                Ir a Mis Artículos
            </a>
        </div>
    </div>

    <script src="{{ static_asset('assets/js/jquery.min.js') }}"></script>
    <script>

        $(function () {
            $('.load_articles').on('click', function () {
                $.ajax({
                    url: `/load_articles`,
                    type: 'GET',
                    success: function (response) {
                        let articlesList = '';
                        if (Array.isArray(response.articles)) {
                            response.articles.forEach(function (article) {
                                let productName = article.product_name;
                                if (isUpperCase(productName)) {
                                    productName = capitalizeWords(productName.toLowerCase());
                                }
                                articlesList += `
                            <li class="list-group-item d-flex justify-content-between lh-condensed my-1 hover-article">
                                            <span class="article-icon">
                                                <img class="cat-image lazyload mr-2 opacity-60 icon-size"
                                                data-src="${article.category_icon_url}"
                                                width="16" alt="${article.category_name}">
                                            </span>
                                <div class="w-75">
                                    <a href="javascript:void(0)" id="${article.category_id}" class="load_new_article hov-bg-black-10">
                                        <span class="category_text_o" data-src="${article.category_name}"></span>
                                        <span class="text-muted d-block w-100 mb-0 mt-1">${productName}</span>
                                        <!--<small class="text-muted font-weight-bold">Año: ${article.year_name}</small>-->
                                    </a>
                                </div>
                            </li>
                        `;
                            });

                            $('#articles-container').html(articlesList);
                        } else {
                            console.error('La respuesta no contiene el array esperado:', response);
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr, ' ERRR');
                    }
                });
            });
            $('#articles-container').on('click', '.load_new_article', function (e) {
                e.preventDefault();
                const imageUrl = $(this).closest('li').find('img').attr('data-src');
                const text = $(this).closest('li').find('span.category_text_o').attr('data-src');
                console.log(text);

                if (imageUrl) {
                    $("#category_text").html(text);
                    $('.dropdown-toggle .circle').html(`
                        <img src="${imageUrl}" alt="Icon" style="width: 100%; height: 100%; border-radius: 50%;">
                    `).css('background-color', 'transparent');
                }

                var articleId = $(this).attr('id');
                var userId = <?= Auth::user()->id ?>;

                $.ajax({
                    url: '/get-articles-by-id',
                    type: 'POST',
                    data: {
                        article_id: articleId,
                        user_id: userId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        console.log('response', response.html);
                        if (response) {
                            $('#section_articles').html(`
                            <div id="preloader" style="display: flex;
                                                                justify-content: center;
                                                                height: 50px;">
                                <img src="{{ static_asset('assets/css/ajax-loader.gif') }}" alt="Loading..." />
                            </div>
                        `);
                            setTimeout(function () {
                                $('#section_articles').html(response.html);
                                AIZ.plugins.slickCarousel();
                            }, 400);
                        } else {
                            console.log('no data')
                        }
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr.responseJSON.message);
                    }
                });
            });
        });

        function isUpperCase(str) {
            return str === str.toUpperCase();
        }

        function capitalizeWords(str) {
            return str.replace(/\b\w/g, function (char) {
                return char.toUpperCase();
            });
        }


    </script>

@endif


