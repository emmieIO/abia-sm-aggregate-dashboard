import './bootstrap';
import './charts';
import './studentData'
import 'preline';
import { createIcons, icons } from 'lucide';
import Alpine from 'alpinejs'


createIcons({ icons });

window.alpine = Alpine;

Alpine.start();