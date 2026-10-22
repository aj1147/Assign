
document.addEventListener("DOMContentLoaded", function () {

    // ==========================================
    // 1. ASSIGNMENT FORM VALIDATION
    // ==========================================

    const form = document.querySelector('form[action="add.php"]');

    if (form) {

        const title = document.getElementById("title");
        const subject = document.getElementById("subject");
        const description = document.getElementById("description");
        const dueDate = document.getElementById("due_date");

        form.addEventListener("submit", function (event) {

            // Remove unnecessary spaces
            title.value = title.value.trim();
            subject.value = subject.value.trim();
            description.value = description.value.trim();

            // Validate assignment title
            if (title.value === "") {
                event.preventDefault();
                alert("Please enter the assignment title.");
                title.focus();
                return;
            }

            // Validate subject
            if (subject.value === "") {
                event.preventDefault();
                alert("Please enter the subject.");
                subject.focus();
                return;
            }

            // Validate due date
            if (dueDate.value === "") {
                event.preventDefault();
                alert("Please select a due date.");
                dueDate.focus();
                return;
            }

            // Check whether the selected date is valid
            const selectedDate = new Date(dueDate.value + "T00:00:00");

            if (Number.isNaN(selectedDate.getTime())) {
                event.preventDefault();
                alert("Please select a valid due date.");
                dueDate.focus();
                return;
            }

            // Prevent selecting a date in the past
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate < today) {
                event.preventDefault();
                alert("The due date cannot be in the past.");
                dueDate.focus();
                return;
            }

        });

    }


    // ==========================================
    // 2. DELETE ASSIGNMENT CONFIRMATION
    // ==========================================

    const deleteButtons = document.querySelectorAll(".delete-button");

    deleteButtons.forEach(function (button) {

        button.addEventListener("click", function (event) {

            const confirmed = confirm(
                "Are you sure you want to delete this assignment?"
            );

            if (!confirmed) {
                event.preventDefault();
            }

        });

    });


    // ==========================================
    // 3. CURRENT YEAR IN FOOTER
    // ==========================================

    const currentYear = document.getElementById("currentYear");

    if (currentYear) {
        currentYear.textContent = new Date().getFullYear();
    }


    // ==========================================
    // 4. SUCCESS MESSAGE DISPLAY
    // ==========================================

    const statusMessage = document.querySelector('[role="status"]');

    if (statusMessage && statusMessage.textContent.trim() !== "") {

        statusMessage.scrollIntoView({
            behavior: "smooth",
            block: "center"
        });

    }

});
