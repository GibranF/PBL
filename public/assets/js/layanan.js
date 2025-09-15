
  document.addEventListener('DOMContentLoaded', function () {
    const seeMoreButtons = document.querySelectorAll('.btn-see-more');
    seeMoreButtons.forEach(button => {
      button.addEventListener('click', function () {
        const cardText = this.previousElementSibling;
        cardText.classList.toggle('expanded');
        this.textContent = cardText.classList.contains('expanded') ? 'Sembunyikan' : 'Lihat Selengkapnya';
      });
    });
  });

  AOS.init({
    duration: 1000,
    once: true,
  });
