document.addEventListener("DOMContentLoaded", function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, 500); // Add a delay of 300ms (adjust as needed)
                observer.unobserve(entry.target); // Stop observing after it becomes visible
            }
        });
    });

    document.querySelectorAll('.hidden').forEach(element => {
        observer.observe(element);
    });
});





