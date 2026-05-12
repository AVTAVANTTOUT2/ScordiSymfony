/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    './templates/**/*.twig',
    './assets/**/*.js'
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif']
      },
      colors: {
        'bg-deepest': '#1a1b1e',
        'bg-deep': '#2b2d31',
        'bg-base': '#313338',
        'bg-elevated': '#383a40',
        'bg-hover': '#404249',
        'border-subtle': '#3f4147',
        'border-default': '#4a4c52',
        'text-primary': '#f2f3f5',
        'text-secondary': '#b5bac1',
        'text-muted': '#80848e',
        'text-link': '#00a8fc',
        brand: '#5865f2',
        'brand-hover': '#4752c4',
        'status-online': '#23a55a',
        'status-idle': '#f0b232',
        'status-dnd': '#f23f43',
        'status-offline': '#80848e',
        success: '#23a55a',
        warning: '#f0b232',
        danger: '#f23f43',
        info: '#00a8fc'
      },
      keyframes: {
        'message-in': {
          '0%': { opacity: '0', transform: 'translateY(4px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' }
        },
        'modal-in': {
          '0%': { opacity: '0', transform: 'scale(0.95)' },
          '100%': { opacity: '1', transform: 'scale(1)' }
        },
        'slide-in-right': {
          '0%': { opacity: '0', transform: 'translateX(24px)' },
          '100%': { opacity: '1', transform: 'translateX(0)' }
        },
        'presence-pulse': {
          '0%, 100%': { boxShadow: '0 0 0 0 rgba(35, 165, 90, 0.6)' },
          '70%': { boxShadow: '0 0 0 8px rgba(35, 165, 90, 0)' }
        }
      },
      animation: {
        'message-in': 'message-in 150ms ease-out',
        'modal-in': 'modal-in 150ms ease-out',
        'slide-in-right': 'slide-in-right 180ms ease-out',
        'presence-pulse': 'presence-pulse 1.4s infinite'
      }
    }
  },
  plugins: []
};
