import { startStimulusApp } from '@symfony/stimulus-bundle';
import ChatController from './controllers/chat_controller.js';
import PresenceController from './controllers/presence_controller.js';
import ThemeController from './controllers/theme_controller.js';
import ModalController from './controllers/modal_controller.js';
import DrawerController from './controllers/drawer_controller.js';
import TooltipController from './controllers/tooltip_controller.js';
import ToastController from './controllers/toast_controller.js';
import AutoresizeController from './controllers/autoresize_controller.js';

const app = startStimulusApp();
app.register('chat', ChatController);
app.register('presence', PresenceController);
app.register('theme', ThemeController);
app.register('modal', ModalController);
app.register('drawer', DrawerController);
app.register('tooltip', TooltipController);
app.register('toast', ToastController);
app.register('autoresize', AutoresizeController);
