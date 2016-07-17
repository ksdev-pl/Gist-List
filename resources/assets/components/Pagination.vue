<style>
    .pagination {
        margin: 0;
    }

    .pagination-info {
        padding-top: 7px;
    }
</style>

<template>
    <div class="pull-left pagination-info">
        Showing {{ (offset + 1) < numRows ? (offset + 1) : numRows }}
        to {{ (offset + limit) < numRows ? offset + limit : numRows }}
        of {{ numRows }} entries
    </div>
    <nav class="pull-right">
        <ul class="pagination">
            <li :class="{'disabled': page == 0}">
                <a href="#"
                   @click.prevent="changePage(page == 0 ? 0 : page - 1)">
                    <i class="fa fa-angle-left"></i>
                </a>
            </li>
            <li v-for="num in numPages"
                :class="{'active': page == num, 'disabled': isPaginationDots(num)}"
                v-if="num == 0
                    || num == numPages - 1
                    || (num >= (page - 3) && num <= (page + 3))
                    || isPaginationDots(num)">
                <a href="#" v-if="isPaginationDots(num)">
                    &#8230;
                </a>
                <a href="#" v-else @click.prevent="changePage(num)">
                    {{ num + 1 }}
                </a>
            </li>
            <li :class="{'disabled': numPages == 0 || page == numPages - 1}">
                <a href="#"
                   @click.prevent="changePage(page == numPages - 1 ? numPages - 1 : page + 1)">
                    <i class="fa fa-angle-right"></i>
                </a>
            </li>
        </ul>
    </nav>
</template>

<script type="text/ecmascript-6">
    module.exports = {
        props: {
            numRows: Number,
            limit: Number,
            page: Number,
            offset: Number
        },
        computed: {
            numPages() {
                return Math.ceil(this.numRows / this.limit);
            }
        },
        methods: {
            changePage(num) {
                this.page = num;
            },
            isPaginationDots(num) {
                return (num < (this.page - 3) && num == 1) || (num > this.page + 3 && num == this.numPages - 2);
            }
        },
        watch: {
            'page + limit'() {
                this.offset = this.page * this.limit;
            }
        }
    }
</script>
