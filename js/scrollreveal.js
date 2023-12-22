const sr = ScrollReveal({
    origin: '',
    distance: '30px',
    duration: 2000,
    reset: true
});

sr.reveal(`.heading`, {
interval: 200
})

const sl = ScrollReveal({
    origin: 'left',
    distance: '30px',
    duration: 2000,
    reset: true
});

sl.reveal(`.box`, {
interval: 200
})