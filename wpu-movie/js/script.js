// Fungsi untuk melakukan pencarian film
function fetchMovies() {
  const query = $('#search-input').val().trim();
  if (query === '') return;

  $.ajax({
    url: 'https://www.omdbapi.com',
    type: 'get',
    dataType: 'json',
    data: {
      'apikey': '52ac2799',
      's': query
    },
    success: function(result) {
      if (result.Response == "True") {
        let movies = result.Search;
        let htmlContent = '';

        $.each(movies, function(i, data) {
          htmlContent += `
            <div class="col-md-4 my-3">
              <div class="card h-100">
                <img src="${data.Poster}" class="card-img-top" alt="${data.Title}">
                <div class="card-body">
                  <h5 class="card-title">${data.Title}</h5>
                  <h6 class="card-subtitle mb-2 text-muted">${data.Year}</h6>
                  <a href="#" class="btn btn-primary btn-detail" data-id="${data.imdbID}" data-toggle="modal" data-target="#exampleModal">See Detail</a>
                </div>
              </div>
            </div>
          `;
        });

        $('#movie-list').html(htmlContent);
      } else {
        $('#movie-list').html(`
          <div class="col">
            <h3 class="text-center">${result.Error}</h3>
          </div>
        `);
      }
    }
  });
}

// Jalankan saat dokumen siap
$(document).ready(function() {
  // Klik tombol Search
  $('#search-button').on('click', function() {
    fetchMovies();
  });

  // Tekan Enter untuk Search
  $('#search-input').on('keypress', function(e) {
    if (e.which == 13) {
      fetchMovies();
    }
  });

  // Klik tombol "See Detail"
  $('#movie-list').on('click', '.btn-detail', function() {
    const imdbID = $(this).data('id');

    $.ajax({
      url: 'https://www.omdbapi.com',
      type: 'get',
      dataType: 'json',
      data: {
        'apikey': '52ac2799',
        'i': imdbID
      },
      success: function(movie) {
        if (movie.Response === "True") {
          $('#exampleModalLabel').text(movie.Title);
          $('.modal-body').html(`
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-4">
                  <img src="${movie.Poster}" class="img-fluid" />
                </div>
                <div class="col-md-8">
                  <ul class="list-group">
                    <li class="list-group-item"><strong>Released:</strong> ${movie.Released}</li>
                    <li class="list-group-item"><strong>Genre:</strong> ${movie.Genre}</li>
                    <li class="list-group-item"><strong>Director:</strong> ${movie.Director}</li>
                    <li class="list-group-item"><strong>Actors:</strong> ${movie.Actors}</li>
                    <li class="list-group-item"><strong>Plot:</strong> ${movie.Plot}</li>
                  </ul>
                </div>
              </div>
            </div>
          `);
        }
      }
    });
  });
});
