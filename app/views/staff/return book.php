<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Book Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom border-1 border-danger">
                <h5 class="modal-title">Return Book Management</h5>
                <button type="button" class="btn"><i class="fa fa-close"></i></button>
            </div>
            <div class="modal-body">
            <form id="returnBookForm" novalidate>
          <div class="mb-3 row align-items-center">
            <label for="returnDate" class="col-sm-4 col-form-label">Return Date</label>
            <div class="col-sm-8">
              <input type="date" class="form-control" id="returnDate" placeholder="YYYY-MM-DD">
              <span class="text-danger" id="returnDateError"></span>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="amount" class="col-sm-4 col-form-label">Rs</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="amount" placeholder="Enter amount" onchange="generateFine();">
              <span class="text-danger" id="amountError"></span>
            </div>
          </div>

          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Return Book</button>
          </div>
        </form>
            </div>
        </div>
    </div>
    <script>
    // Validate Return Date field
    function validateReturnDate() {
      const returnDate = document.getElementById('returnDate').value.trim();
      const errorSpan = document.getElementById('returnDateError');
      const dateRegex = /^\d{4}-\d{2}-\d{2}$/;  // Format: YYYY-MM-DD

      if (!returnDate) {
        errorSpan.textContent = "Return date is required.";
        return false;
      } else if (!dateRegex.test(returnDate)) {
        errorSpan.textContent = "Enter a valid date in YYYY-MM-DD format.";
        return false;
      } else {
        errorSpan.textContent = "";
        return true;
      }
    }

    // Validate Amount field
    function validateAmount() {
      const amount = document.getElementById('amount').value.trim();
      const errorSpan = document.getElementById('amountError');

      if (!amount) {
        errorSpan.textContent = "Amount is required.";
        return false;
      } else if (isNaN(amount) || parseFloat(amount) <= 0) {
        errorSpan.textContent = "Enter a valid positive number.";
        return false;
      } else {
        errorSpan.textContent = "";
        return true;
      }
    }

    // Form submission validation
    document.getElementById('returnBookForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Prevent form submission

      const isReturnDateValid = validateReturnDate();
      const isAmountValid = validateAmount();

      // If both fields are valid, submit the form or perform actions
      if (isReturnDateValid && isAmountValid) {
        alert("Book returned successfully!");
        this.reset(); // Clear form fields after success
      }
    });

    // Real-time validation on blur
    document.getElementById('returnDate').addEventListener('blur', validateReturnDate);
    document.getElementById('amount').addEventListener('blur', validateAmount);
  </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
