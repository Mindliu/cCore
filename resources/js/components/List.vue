<template>
    <div class="row">
        <div>
            <label for="search">Search:</label>
            <input type="text" id="search" class="form-control" v-on:input="debounceSearch" v-model="params.query">
        </div>

        <div class="mt-5">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Domain</th>
                    <th scope="col">Rank</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in list">
                    <td>{{ item.domain }}</td>
                    <td>{{ item.rank }}</td>
                </tr>
                </tbody>
            </table>
            <span
                class="btn btn-secondary float-start"
                @click="prevPage"
                :class="{'disabled': this.currentPage < 2}"
            >< Prev</span>
            <span
                class="btn btn-primary float-end"
                @click="nextPage"
                :class="{'disabled': this.currentPage === this.lastPage}"
            >Next ></span>
        </div>
    </div>
</template>

<script>
import _ from 'lodash';

export default {
    name: 'List',
    data() {
        return {
            list: null,
            currentPage: 1,
            lastPage: null,
            params: {
                query: null,
                limit: 100,
                page: 1,
            },
            errors: {},
        }
    },
    methods: {
        fetchList() {
            console.log(this.params);
            this.axios.get('/api/page-rank-list', {
                params: {
                    q: this.params.query,
                    limit: this.params.limit,
                    page: this.currentPage,
                },
            })
                .then((response) => {
                    this.errors = {}
                    this.list = response.data.data
                    this.lastPage = response.data.meta.last_page
                })
                .catch((errors) => {
                    this.errors = errors.response.data.errors
                })
                .finally()
        },
        prevPage() {
            this.currentPage = this.currentPage - 1 < 2 ? 1 : this.currentPage - 1;

            this.fetchList()
        },
        nextPage() {
            this.currentPage += 1

            this.fetchList()
        },
        debounceSearch: _.debounce(function() {
            this.fetchList();
        }, 1000)

    },
    mounted() {
        this.fetchList();
    }
}
</script>
