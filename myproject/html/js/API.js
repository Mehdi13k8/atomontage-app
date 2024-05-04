// crud request
// GET, POST, PUT, DELETE

// ready
$(document).ready(function () {
  // GET ALL BOOKS
  // Optionally, show a loading indicator
  $("#loading").show();

  function loadPage() {
    $.ajax({
      url: "getBooks.php",
      type: "GET",
      dataType: "json", // Expecting JSON response
      success: function (response) {
        $("#loading").hide(); // Hide loading indicator

        if (response.success) {
          console.log("Books fetched successfully:", response.data); // Log successful data

          // Dynamically build table rows and append to the table body
          let books = response.data;
          let rows = "";
          for (let i = 0; i < books.length; i++) {
            rows += `<tr>
            <td>${books[i].title}</td>
            <td>${books[i].author}</td>
            <td>${books[i].genre}</td>
            <td>
              <button class="btn btn-primary btn-sm editBtn" data-id="${books[i].id}">Edit</button>
              <button class="btn btn-danger btn-sm deleteBtn" data-id="${books[i].id}">Delete</button>
            </td>
          </tr>`;
          }
          $(".booksDisplay").html(rows);
        } else {
          console.error("Error fetching books: ", response.message);
          // $("#booksDisplay").html(
          //   "<p>Error loading books. Please try again.</p>" // Show error in HTML
          // );
        }
      },
      error: function (xhr, status, error) {
        $("#loading").hide(); // Hide loading indicator
        console.error("Error fetching books: ", error);
        //   $("#booksDisplay").html(
        //     "<p>Failed to fetch books due to a network or server error.</p>" // Show network/server error
        //   );
      },
    });
  }

  loadPage();

  // Handle Add Book Form Submission
  $("#addBookForm").submit(function (event) {
    event.preventDefault(); // Prevent the form from submitting through the browser

    var bookData = {
      title: $("#title").val(),
      author: $("#author").val(),
      genre: $("#genre").val(),
    };

    $.ajax({
      url: "createBook.php",
      type: "POST",
      contentType: "application/json", // Set the content type of the request
      data: JSON.stringify(bookData),
      success: function (response) {
        var res = JSON.parse(response);
        if (res.success) {
          alert(res.message);
          $("#booksDisplay").append(
            "<tr><td>" +
              bookData.title +
              "</td><td>" +
              bookData.author +
              "</td><td>" +
              bookData.genre +
              "</td><td><button class='btn btn-primary'>Edit</button> <button class='btn btn-danger'>Delete</button></td></tr>"
          );
          // Clear form fields
          $("#title").val("");
          $("#author").val("");
          $("#genre").val("");
          loadPage();
        } else {
          alert(res.message);
        }
      },
      error: function () {
        alert("Error adding book.");
      },
    });
  });

  // Delete book event
  $(document).on("click", ".deleteBtn", function () {
    var bookId = $(this).data("id"); // Retrieve the data-id attribute of the button

    if (confirm("Are you sure you want to delete this book?")) {
      $.ajax({
        url: "deleteBook.php",
        type: "POST",
        data: { id: bookId },
        success: function (response) {
          var res = JSON.parse(response);
          if (res.success) {
            alert(res.message);
            // Remove the book row from the table
            $("tr#book-" + bookId).remove();
            loadPage();
        } else {
            alert(res.message);
          }
        },
        error: function () {
          alert("Error deleting book.");
        },
      });
    }
  });
});
