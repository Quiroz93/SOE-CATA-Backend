import defaultTheme from 'tailwindcss/defaultTheme';

/**
 * DESIGN SYSTEM SENA - Manual de Identidad Visual 2024 (alineación estricta)
 */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        sena: {
          primary: '#39A900',   // Verde institucional oficial
          dark: '#1F5F00',      // Verde soporte
          light: '#E6F4DD',     // Fondo institucional
          black: '#000000',
          white: '#FFFFFF',
          gray: '#4B5563'
        },
      },
      fontFamily: {
        sans: ['Work Sans', ...defaultTheme.fontFamily.sans],
      },
      borderRadius: {
        sena: '0.5rem',
      }
    },
  },
  plugins: [],
};
