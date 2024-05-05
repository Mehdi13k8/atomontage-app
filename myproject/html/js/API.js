// crud request
// GET, POST, PUT, DELETE

function ajaxSetup() {
  $.ajaxSetup({
    beforeSend: function (xhr) {
      xhr.setRequestHeader("Authorization", localStorage.getItem("token"));
    },
  });
}

// ready
$(document).ready(function () {
  ajaxSetup();
  if (!localStorage.getItem("token")) {
    window.location.href = "login.html"; // Redirect to login if no token
  }

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

  $("#logoutButton").click(function () {
    $.ajax({
      type: "POST",
      url: "logout.php",
      headers: {
        Authorization: localStorage.getItem("token"),
      },
      success: function (response) {
        const data = JSON.parse(response);
        if (data.success) {
          localStorage.removeItem("token"); // Remove the token from localStorage
          window.location.href = "login.html"; // Redirect to the login page
        } else {
          alert(data.message);
          console.log(data.message);
        }
      },
      error: function () {
        alert("Logout request failed.");
      },
    });
  });

  $("body").on("click", ".editBtn", function () {
    var bookId = $(this).data("id");
    var row = $(this).closest("tr");
    var title = row.find("td:eq(0)").text();
    var author = row.find("td:eq(1)").text();
    var genre = row.find("td:eq(2)").text();

    // Populate the edit form
    $("#editId").val(bookId);
    $("#editTitle").val(title);
    $("#editAuthor").val(author);
    $("#editGenre").val(genre);

    // Show the form
    $("#editBookForm").show();
  });

  // Handle the update form submission
  $("#updateBookForm").submit(function (e) {
    e.preventDefault();
    var bookId = $("#editId").val();
    var updatedData = {
      id: bookId,
      title: $("#editTitle").val(),
      author: $("#editAuthor").val(),
      genre: $("#editGenre").val(),
    };

    // AJAX request to update the book
    $.ajax({
      url: "updateBook.php", // Server-side script to handle the update
      type: "POST",
      data: JSON.stringify(updatedData),
      contentType: "application/json",
      success: function (response) {
        if (response.success) {
          // Refresh the list or directly update the table row
          alert("Book updated successfully!");
          location.reload(); // Or update the row data without reloading
        } else {
          console.error("Error updating book: ", response.success, response);
          alert("Error: " + response.message);
        }
      },
      error: function () {
        alert("Failed to update the book.");
      },
    });
  });
});
