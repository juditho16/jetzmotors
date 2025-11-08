<div class="container mt-3">
  <h4><i class="bi bi-calendar-event me-2"></i> Book Appointment</h4>
  <form method="POST" action="../functions/book_appointment.php">
    <div class="mb-3">
      <label>Service Type</label>
      <select class="form-select" name="service_id" required>
        <option value="1">Oil Change</option>
        <option value="2">Brake Check</option>
        <option value="3">Full Service</option>
      </select>
    </div>
    <div class="mb-3">
      <label>Date</label>
      <input type="date" name="date" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Time</label>
      <input type="time" name="time" class="form-control" required>
    </div>
    <button class="btn btn-primary w-100">Book</button>
  </form>
</div>
