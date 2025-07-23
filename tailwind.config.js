module.exports = {
  content: [
    './public/**/*.html',
    './app/controllers/**/*.php',
    './ressources/views/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#31B14B', // Vert principal
          dark: '#32A855',
        },
        secondary: {
          DEFAULT: '#335BD4', // Bleu principal
          light: '#2B51C8',
        },
        accent: '#F6C700', // Jaune accent
      },
    },
  },
  plugins: [],
}; 