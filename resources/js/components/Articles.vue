<template>
    <div>
        <h2 class="my-4">Articles</h2>

        <form @submit.prevent="updateOrCreate()" class="mb-3">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Title" v-model="article.title">
            </div>

            <div class="form-group">
                <textarea class="form-control" placeholder="Body" v-model="article.body"></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Save</button>
        </form>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prevPageUrl}]" class="page-item">
                    <a class="page-link" href="javascript:void(0)" @click="fetchArticles(pagination.prevPageUrl)">Previous</a>
                </li>

                <li class="page-item disabled">
                    <a class="page-link text-dark" href="javascript:void(0)">Page {{ pagination.currentPage }} of {{ pagination.lastPage }}</a>
                </li>


                <li class="page-item" v-bind:class="[{disabled: !pagination.nextPageUrl}]">
                    <a class="page-link" @click="fetchArticles(pagination.nextPageUrl)" href="javascript:void(0)">Next</a>
                </li>
            </ul>
        </nav>

        <div class="card card-body mb-2" v-for="article in articles" v-bind:key="article.id">
            <h3>{{ article.title }}</h3>
            <p>{{ article.body }}</p>
            <hr>
            <button class="btn btn-warning mb-2" @click="editArticle(article)">Edit</button>
            <button class="btn btn-danger" @click="deleteArticle(article.id)">Delete</button>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                articles: [],
                article: {
                    id: '',
                    title: '',
                    body: '',
                },
                pagination: {},
                edit: false,
            }
        },

        created() {
            this.fetchArticles();
        },

        methods: {
            async fetchArticles(pageUrl = '/api/articles') {
                const response = await axios.get(pageUrl);

                console.log(response.data);
                this.makePagination(response.data.meta, response.data.links);

                this.articles = response.data.data;
            },

            makePagination(meta, links) {
                this.pagination = {
                    currentPage: meta.current_page,
                    lastPage: meta.last_page,
                    nextPageUrl: links.next,
                    prevPageUrl: links.prev,
                }
            },

            async updateOrCreate() {
                if (this.edit === false) {
                    await axios.post('/api/articles', this.article);
                } else {
                    await axios.put(`/api/articles/${this.article.id}`, this.article);
                }

                this.fetchArticles();
                this.article = {
                    id: '',
                    title: '',
                    body: '',
                };
            },

            async editArticle(article) {
                this.edit = true;
                this.article = {
                    id: article.id,
                    title: article.title,
                    body: article.body,
                }
            },

            async deleteArticle(id) {
                await axios.delete(`/api/articles/${id}`);
                this.fetchArticles();
            },
        }
    }
</script>
