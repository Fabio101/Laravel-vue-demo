<template>
    <div class="card">
        <div class="card-body">
            <form @submit.prevent="movieForm">
                <div class="form-group">
                    Name: <input class="form-control" type="text" name="name" v-model="movie.name"><br>
                    Detail: <input class="form-control" type="text" name="detail" v-model="movie.detail"><br>
                    Year: <select class="form-control" type="text" name="year" v-model="movie.year">
                            <option v-for="year in years">{{ year }}</option>
                        </select><br>
                    Watched: <select class="form-control" type="text" name="watched" v-model="movie.watched">
                                <option v-for="watch in watched">{{ watch }}</option>
                            </select><br>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
            <div class="card card-body mb-2" v-for="(movie, index) in movies">
                    <table style="table-layout: fixed; width:100%; word-wrap:break-word">
                        <tr>
                            <th>Name</th>
                            <th>Details</th>
                            <th>Year</th>
                            <th>Watched</th>
                        </tr>
                        <tr>
                            <td>{{ movie.name }}</td>
                            <td>{{ movie.detail }}</td>
                            <td>{{ movie.year }}</td>
                            <td>{{ movie.watched }}</td>
                            <td>
                                <button @click="editMovie(movie)" class="btn btn-warning">Edit</button>
                            </td>
                            <td>
                                <button @click="deleteMovie(movie.id)" class="btn btn-danger">Delete</button>
                            </td>
                        </tr>
                    </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                movies: [],
                movie: [{
                    id: '',
                    name: '',
                    detail: '',
                    year: '',
                    watched: ''
                }],
                movie_id: '',
                edit: false,
                years: ['1988', '1989', '1990', '1991', '1992', '1993', '1994', '1995', '1996', '1997', '1998'],
                watched: ['Yes', 'No']
            }
        },

        created() {
            this.fetchMovies();
        },

        methods:{
            fetchMovies() {
                fetch('/movies')
                    .then(response => response.json())
                    .then(response => {this.movies = response})
            },
            movieForm() {
                let watched = 0;

                if (this.movie.watched === 'Yes') {
                    watched = 1;
                }

                if (this.edit === false)
                {
                    fetch('/movies', {
                        method: 'POST',
                        body: 'name=' + this.movie.name +
                            '&detail=' + this.movie.detail +
                            '&year=' + this.movie.year +
                            '&watched=' + watched,
                        headers:
                            {
                                "Content-Type": "application/x-www-form-urlencoded"
                            }
                    })
                    .then(this.fetchMovies());
                    this.movie.name = '';
                    this.movie.detail = '';
                    this.movie.year = '';
                    this.movie.watched = '';
                } else {
                    fetch('/movies/' + this.movie_id, {
                        method: 'PUT',
                        body: 'name=' + this.movie.name +
                            '&detail=' + this.movie.detail +
                            '&year=' + this.movie.year +
                            '&watched=' + watched,
                        headers:
                            {
                                "Content-Type": "application/x-www-form-urlencoded"
                            }
                    })
                    .then(this.fetchMovies());
                    this.movie.name = '';
                    this.movie.detail = '';
                    this.movie.year = '';
                    this.movie.watched = '';
                }
            },
            editMovie(movie) {
                this.edit = true;
                this.movie_id = movie.id;
                this.movie.name = movie.name;
                this.movie.detail = movie.detail;
                this.movie.year = movie.year;
                this.movie.watched = movie.watched;
                this.fetchMovies();

            },
            deleteMovie(movieID) {
                if (confirm('Are You Sure?')) {
                    fetch(`/movies/${movieID}`, {
                        method: 'delete'
                    })
                    .then(this.fetchMovies())
                }
            }
        }
    }
</script>
