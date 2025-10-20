
# Technical Specification: Moodle Interactive Board Plugin

## Project Overview

### Plugin Name Options

**Primary Choice: "Mosaic"**  (`mod_mosaic`)

-   Conveys the visual, piece-by-piece collaborative nature
-   Modern and memorable
-   Easy to pronounce internationally

**Alternative Names:**

-   **FlowBoard**  (`mod_flowboard`) - Emphasises fluid, dynamic interaction
-   **Canvas Collaborate**  (`mod_canvascollab`) - Clear purpose, creative connotation
-   **Nexus Board**  (`mod_nexusboard`) - Suggests connections and central hub
-   **Prism Board**  (`mod_prismboard`) - Multiple perspectives, colorful

### Project Mission

Create a modern, visually appealing, and highly interactive collaborative board plugin for Moodle that rivals commercial solutions like Padlet while maintaining full integration with Moodle's educational ecosystem.

### Core Objectives

1.  **Superior User Experience**: Beautiful, intuitive interface that delights users
2.  **Rich Interactivity**: Real-time collaboration with smooth animations and transitions
3.  **Flexible Layouts**: Multiple visual organisation patterns for different use cases
4.  **Media-Rich Content**: Seamless handling of images, videos, documents, and embeds
5.  **Educational Focus**: Built-in features for teaching, assessment, and student engagement
6.  **Accessibility**: WCAG 2.1 AA compliant, fully keyboard navigable
7.  **Mobile-First**: Responsive design with touch gesture support

### Target Users

-   **Primary**: Educators creating interactive learning activities
-   **Secondary**: Students collaborating on projects and presentations
-   **Tertiary**: Corporate trainers and workshop facilitators

----------

## Feature Specification

### Core Features (MVP - Phase 1)

#### 1. Multiple Layout Types

-   **Wall/Masonry**: Pinterest-style cascading grid
-   **Grid**: Uniform card arrangement
-   **Canvas**: Free-positioning with connection lines
-   **Stream**: Linear blog/social media style
-   **Timeline**: Chronological with visual timeline

#### 2. Rich Content Cards

-   **Text**: Rich text editor with formatting (using TinyMCE)
-   **Images**: Drag-and-drop upload with automatic optimisation
-   **Videos**: YouTube/Vimeo embeds + direct upload
-   **Audio**: Voice recordings and audio file uploads
-   **Files**: PDF, Word, PowerPoint previews
-   **Code**: Syntax-highlighted code snippets
-   **Links**: Rich link previews with OpenGraph data

#### 3. Interactive Elements

-   **Reactions**: Emoji reactions (👍 ❤️ 🎉 💡 🤔)
-   **Comments**: Threaded discussions on cards
-   **Voting**: Star ratings or upvoting system
-   **Tags**: Categorisation and filtering

#### 4. Collaboration Features

-   **Real-time Updates**: Live synchronisation across users
-   **User Presence**: See who's currently viewing/editing
-   **Permissions**: Granular control (view/post/edit/moderate)
-   **Attribution**: Optional anonymous posting

#### 5. Customisation

-   **Themes**: Pre-designed beautiful backgrounds and color schemes
-   **Templates**: Ready-made boards for common educational activities
-   **Sections**: Organise content under headers/categories
-   **Branding**: Custom backgrounds, logos, colors

### Advanced Features (Phase 2)

#### 1. AI-Powered Features

-   **Smart Suggestions**: AI-generated content ideas
-   **Auto-Categorisation**: Intelligent grouping of similar posts
-   **Content Moderation**: Automatic inappropriate content detection
-   **Translation**: Multi-language support with auto-translation

#### 2. Analytics & Assessment

-   **Participation Tracking**: Detailed engagement metrics
-   **Grading Integration**: Direct connection to Moodle gradebook
-   **Rubric Support**: Built-in assessment criteria
-   **Export Reports**: PDF/Excel analytics exports

#### 3. Advanced Interactions

-   **Polls & Quizzes**: Embedded interactive elements
-   **Whiteboard Drawing**: Collaborative sketching tools
-   **Mind Mapping**: Visual connection tools in canvas mode
-   **Presentation Mode**: Full-screen slideshow of content

### Comparison with Existing Solutions

| Feature | Moodle Board | Padlet | Mosaic (Ours) |
|---------|--------------|---------|---------------------| 
| Layouts | Column only | 8+ types | 5+ types | 
| Real-time sync | Polling |WebSocket | WebSocket/SSE | 
| Rich media | Basic | Excellent | Excellent | 
| UI/UX | Dated | Modern | Modern + Beautiful | 
| Anonymous posting | Yes (only) | Optional | Optional | 
| Templates | No | Yes (60+) | Yes (20+ educational) | 
| AI features | No | Yes | Yes (Phase 2) | 
| Reactions | Stars only | Emojis | Emojis + Custom | 
| Mobile support | Basic | Good | Excellent | 
| Offline mode | No | No | Yes (Phase 2) | ---

## Technical Architecture

### Technology Stack

#### Frontend (Vue.js SPA)

```
Vue.js 3.x (Composition API)
├── Vuex 4.x (State Management)
├── Vue Router 4.x (Navigation)
├── Vuelidate (Form Validation)
├── Vue Draggable Plus (Drag & Drop)
├── Floating Vue (Tooltips/Popovers)
└── Pinia (Alternative State Management)

```

#### Build Tools

```
Webpack 5.x
├── Babel (ES6+ Transpilation)
├── PostCSS (CSS Processing)
├── ESLint (Code Quality)
├── Prettier (Code Formatting)
└── Husky (Git Hooks)

```

#### Backend (Moodle PHP)

```
Moodle 4.0+ Compatibility
├── Web Services (REST API)
├── Events API (Activity Logging)
├── File API (Media Handling)
├── Cache API (Performance)
└── Privacy API (GDPR Compliance)

```

#### Real-time Communication

```
Primary: Server-Sent Events (SSE)
Fallback: AJAX Polling
Future: WebSocket Support

```

### Project Structure

```
/mod_mosaic/
├── vue/                              # Vue.js Application
│   ├── src/
│   │   ├── components/
│   │   │   ├── board/
│   │   │   │   ├── BoardContainer.vue
│   │   │   │   ├── BoardHeader.vue
│   │   │   │   └── BoardSettings.vue
│   │   │   ├── cards/
│   │   │   │   ├── CardBase.vue
│   │   │   │   ├── CardText.vue
│   │   │   │   ├── CardMedia.vue
│   │   │   │   └── CardInteractions.vue
│   │   │   ├── layouts/
│   │   │   │   ├── WallLayout.vue
│   │   │   │   ├── GridLayout.vue
│   │   │   │   ├── CanvasLayout.vue
│   │   │   │   ├── StreamLayout.vue
│   │   │   │   └── TimelineLayout.vue
│   │   │   └── common/
│   │   │       ├── MediaUploader.vue
│   │   │       ├── EmojiPicker.vue
│   │   │       └── UserAvatar.vue
│   │   ├── store/
│   │   │   ├── modules/
│   │   │   │   ├── board.js
│   │   │   │   ├── cards.js
│   │   │   │   └── user.js
│   │   │   └── index.js
│   │   ├── services/
│   │   │   ├── api.js               # Moodle Web Service calls
│   │   │   ├── realtime.js          # SSE/Polling handler
│   │   │   └── media.js             # File upload handling
│   │   ├── styles/
│   │   │   ├── variables.scss
│   │   │   ├── mixins.scss
│   │   │   └── components/
│   │   ├── utils/
│   │   │   ├── permissions.js
│   │   │   └── validators.js
│   │   └── App.vue
│   ├── main.js                      # Entry point
│   ├── package.json
│   └── webpack.config.js
│
├── amd/                              # AMD modules
│   ├── build/
│   │   └── app-lazy.min.js          # Webpack output
│   └── src/
│       ├── app-lazy.min.js          # Symlink to build
│       └── loader.js                # AMD loader module
│
├── classes/
│   ├── board.php                    # Board model
│   ├── card.php                     # Card model
│   ├── privacy/
│   │   └── provider.php             # GDPR compliance
│   ├── external/                    # Web Services
│   │   ├── get_board.php
│   │   ├── create_card.php
│   │   ├── update_card.php
│   │   ├── delete_card.php
│   │   ├── add_reaction.php
│   │   └── get_updates.php          # For polling
│   ├── event/                       # Event observers
│   │   ├── board_viewed.php
│   │   └── card_created.php
│   └── output/
│       └── renderer.php
│
├── db/
│   ├── install.xml                  # Database schema
│   ├── upgrade.php
│   ├── access.php                   # Capabilities
│   ├── services.php                 # Web service definitions
│   └── events.php                   # Event definitions
│
├── templates/
│   ├── board.mustache               # Main container
│   └── loading.mustache             # Loading state
│
├── styles/
│   └── styles.css                   # Moodle-specific styles
│
├── lang/
│   └── en/
│       └── mosaic.php          # Language strings
│
├── pix/                              # Icons
│   └── icon.svg
│
├── backup/
│   └── moodle2/                     # Backup/restore
│
├── tests/
│   ├── phpunit/                     # PHP unit tests
│   └── behat/                       # Behat tests
│
├── view.php                          # Main view
├── lib.php                           # Moodle required functions
├── version.php                       # Plugin version
├── settings.php                     # Admin settings
└── README.md                         # Documentation

```

### Database Schema

```sql
-- Main board table
CREATE TABLE mdl_mosaic (
    id BIGINT(10) AUTO_INCREMENT PRIMARY KEY,
    course BIGINT(10) NOT NULL,
    name VARCHAR(255) NOT NULL,
    intro TEXT,
    introformat SMALLINT(4) DEFAULT 1,
    layout VARCHAR(50) DEFAULT 'wall',
    theme_config TEXT,           -- JSON: colors, background, etc.
    settings TEXT,                -- JSON: permissions, features
    template_id BIGINT(10),       -- Reference to template
    timecreated BIGINT(10),
    timemodified BIGINT(10),
    INDEX mdl_mosa_cou_ix (course)
);

-- Cards/Posts table
CREATE TABLE mdl_mosaic_cards (
    id BIGINT(10) AUTO_INCREMENT PRIMARY KEY,
    boardid BIGINT(10) NOT NULL,
    userid BIGINT(10) NOT NULL,
    section_id BIGINT(10),       -- For grouped content
    type VARCHAR(50) DEFAULT 'text',
    title VARCHAR(255),
    content TEXT,
    media_data TEXT,              -- JSON: attachments, embeds
    position_data TEXT,           -- JSON: x,y coords or order
    style_data TEXT,              -- JSON: colors, size
    status TINYINT(1) DEFAULT 1, -- 1=active, 0=deleted
    anonymous TINYINT(1) DEFAULT 0,
    timecreated BIGINT(10),
    timemodified BIGINT(10),
    INDEX mdl_mosacard_boa_ix (boardid),
    INDEX mdl_mosacard_use_ix (userid)
);

-- Reactions table
CREATE TABLE mdl_mosaic_reactions (
    id BIGINT(10) AUTO_INCREMENT PRIMARY KEY,
    cardid BIGINT(10) NOT NULL,
    userid BIGINT(10) NOT NULL,
    reaction VARCHAR(50) NOT NULL,
    timecreated BIGINT(10),
    UNIQUE KEY mdl_mosareac_caruse_uix (cardid, userid, reaction)
);

-- Comments table
CREATE TABLE mdl_mosaic_comments (
    id BIGINT(10) AUTO_INCREMENT PRIMARY KEY,
    cardid BIGINT(10) NOT NULL,
    userid BIGINT(10) NOT NULL,
    parentid BIGINT(10),         -- For threaded comments
    comment TEXT NOT NULL,
    timecreated BIGINT(10),
    timemodified BIGINT(10),
    INDEX mdl_mosacomm_car_ix (cardid)
);

-- Sections/Categories table
CREATE TABLE mdl_mosaic_sections (
    id BIGINT(10) AUTO_INCREMENT PRIMARY KEY,
    boardid BIGINT(10) NOT NULL,
    name VARCHAR(255) NOT NULL,
    color VARCHAR(7),
    position INT(10) DEFAULT 0,
    timecreated BIGINT(10)
);

-- Templates table
CREATE TABLE mdl_mosaic_templates (
    id BIGINT(10) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100),
    config TEXT,                  -- JSON: complete board setup
    is_public TINYINT(1) DEFAULT 0,
    userid BIGINT(10),           -- Creator
    timecreated BIGINT(10)
);

```

### Vue.js Implementation Details

#### Main App Structure (App.vue)

```vue
<template>
  <div id="mosaic-board-app" :class="themeClasses">
    <BoardHeader 
      :board="board"
      :layout="currentLayout"
      @layout-change="handleLayoutChange"
      @settings-open="openSettings"
    />
    
    <transition name="layout-transition" mode="out-in">
      <component 
        :is="layoutComponent"
        :cards="filteredCards"
        :sections="sections"
        @card-create="createCard"
        @card-update="updateCard"
        @card-delete="deleteCard"
      />
    </transition>
    
    <CardModal 
      v-if="showCardModal"
      :card="selectedCard"
      @save="saveCard"
      @close="closeCardModal"
    />
    
    <BoardSettings 
      v-if="showSettings"
      :settings="boardSettings"
      @save="saveSettings"
      @close="closeSettings"
    />
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import { useMoodleAPI } from '@/services/api'
import { useRealtime } from '@/services/realtime'

export default {
  name: 'Mosaic',
  setup() {
    const store = useStore()
    const api = useMoodleAPI()
    const realtime = useRealtime()
    
    // Initialise board
    onMounted(async () => {
      await loadBoard()
      realtime.connect(boardId)
    })
    
    // ... rest of logic
  }
}
</script>

```

#### Webpack Configuration

```javascript
// vue/webpack.config.js
const path = require('path');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
  mode: process.env.NODE_ENV || 'development',
  entry: './main.js',
  output: {
    path: path.resolve(__dirname, '../amd/build'),
    filename: 'app-lazy.min.js',
    libraryTarget: 'amd',
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: 'vue-loader'
      },
      {
        test: /\.js$/,
        loader: 'babel-loader',
        exclude: /node_modules/
      },
      {
        test: /\.(sa|sc|c)ss$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          'postcss-loader',
          'sass-loader'
        ]
      }
    ]
  },
  plugins: [
    new VueLoaderPlugin(),
    new MiniCssExtractPlugin({
      filename: '../../styles/vue-styles.css'
    })
  ],
  externals: {
    // Moodle AMD modules
    'core/ajax': 'core/ajax',
    'core/notification': 'core/notification',
    'core/str': 'core/str',
    'core/templates': 'core/templates'
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'src'),
      'vue$': 'vue/dist/vue.esm.js'
    }
  }
};

```

#### API Service Integration

```javascript
// vue/src/services/api.js
import Ajax from 'core/ajax';

export class MoodleAPI {
  constructor(boardId) {
    this.boardId = boardId;
  }
  
  async getBoard() {
    const [response] = await Ajax.call([{
      methodname: 'mod_mosaic_get_board',
      args: { boardid: this.boardId }
    }]);
    return response;
  }
  
  async createCard(cardData) {
    const [response] = await Ajax.call([{
      methodname: 'mod_mosaic_create_card',
      args: {
        boardid: this.boardId,
        ...cardData
      }
    }]);
    return response;
  }
  
  async updateCard(cardId, updates) {
    const [response] = await Ajax.call([{
      methodname: 'mod_mosaic_update_card',
      args: {
        cardid: cardId,
        updates: JSON.stringify(updates)
      }
    }]);
    return response;
  }
  
  // ... more methods
}

```

### PHP Web Services

```php
// classes/external/get_board.php
namespace mod_mosaic\external;

use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;

class get_board extends external_api {
    
    public static function execute_parameters() {
        return new external_function_parameters([
            'boardid' => new external_value(PARAM_INT, 'Board ID')
        ]);
    }
    
    public static function execute($boardid) {
        global $DB, $USER;
        
        // Validate parameters
        $params = self::validate_parameters(
            self::execute_parameters(), 
            ['boardid' => $boardid]
        );
        
        // Check permissions
        $board = $DB->get_record('mosaic', ['id' => $boardid], '*', MUST_EXIST);
        $context = \context_module::instance($board->cmid);
        self::validate_context($context);
        require_capability('mod/mosaic:view', $context);
        
        // Get cards
        $cards = $DB->get_records('mosaic_cards', [
            'boardid' => $boardid,
            'status' => 1
        ]);
        
        // Get reactions and comments counts
        foreach ($cards as &$card) {
            $card->reactions = $DB->get_records('mosaic_reactions', [
                'cardid' => $card->id
            ]);
            $card->comment_count = $DB->count_records('mosaic_comments', [
                'cardid' => $card->id
            ]);
        }
        
        // Get sections
        $sections = $DB->get_records('mosaic_sections', [
            'boardid' => $boardid
        ], 'position ASC');
        
        return [
            'board' => $board,
            'cards' => array_values($cards),
            'sections' => array_values($sections),
            'permissions' => [
                'can_post' => has_capability('mod/mosaic:post', $context),
                'can_moderate' => has_capability('mod/mosaic:moderate', $context)
            ]
        ];
    }
    
    public static function execute_returns() {
        // Define return structure
        return new external_single_structure([
            'board' => new external_single_structure([
                'id' => new external_value(PARAM_INT),
                'name' => new external_value(PARAM_TEXT),
                'layout' => new external_value(PARAM_TEXT),
                'theme_config' => new external_value(PARAM_RAW),
                'settings' => new external_value(PARAM_RAW)
            ]),
            'cards' => new external_multiple_structure(
                new external_single_structure([
                    'id' => new external_value(PARAM_INT),
                    'type' => new external_value(PARAM_TEXT),
                    'title' => new external_value(PARAM_TEXT),
                    'content' => new external_value(PARAM_RAW),
                    // ... more fields
                ])
            ),
            // ... sections and permissions
        ]);
    }
}

```

### Real-time Updates with SSE

```php
// sse.php - Server-Sent Events endpoint
require_once('../../config.php');
require_login();

$boardid = required_param('boardid', PARAM_INT);

// Verify access
$board = $DB->get_record('mosaic', ['id' => $boardid], '*', MUST_EXIST);
$cm = get_coursemodule_from_instance('mosaic', $board->id);
$context = context_module::instance($cm->id);
require_capability('mod/mosaic:view', $context);

// Set SSE headers
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('X-Accel-Buffering: no'); // Disable Nginx buffering

// Send updates
$lastcheck = time();
while (true) {
    // Check for new cards/updates since last check
    $updates = $DB->get_records_select('mosaic_cards', 
        'boardid = ? AND timemodified > ?', 
        [$boardid, $lastcheck]
    );
    
    if ($updates) {
        echo "event: update\n";
        echo "data: " . json_encode($updates) . "\n\n";
        $lastcheck = time();
    }
    
    // Heartbeat
    echo "event: ping\n";
    echo "data: " . time() . "\n\n";
    
    ob_flush();
    flush();
    
    sleep(2); // Check every 2 seconds
    
    // Timeout after 30 seconds (client will reconnect)
    if (time() - $_SERVER['REQUEST_TIME'] > 30) {
        break;
    }
}

```

----------

## Implementation Phases

### Phase 1: MVP

1.  **Basic infrastructure**

    -   Database schema
    -   Basic CRUD operations
    -   Vue.js setup with Webpack
    -   Wall and Grid layouts
2. **Core features**
    
    -   Text and image cards
    -   Drag and drop
    -   Basic reactions
    -   Real-time updates (polling)
3.  **Polish and testing**
    
    -   Canvas and Stream layouts
    -   Comments system
    -   Mobile responsiveness
    -   Initial templates
4.  **Beta testing**
    
    -   Bug fixes
    -   Performance optimisation
    -   Documentation

### Phase 2: Advanced Features

-   AI integration
-   Advanced analytics
-   SSE/WebSocket support
-   More media types
-   Assessment features

### Phase 3: Enterprise Features

-   White-labeling
-   Advanced permissions
-   Export/import
-   API for external apps
-   Offline support

----------

## Performance Considerations

### Frontend Optimisation

-   Lazy loading of components
-   Virtual scrolling for large boards
-   Image optimisation (WebP with fallbacks)
-   Code splitting for layouts
-   PWA features for offline support

### Backend Optimisation

-   Database indexing on frequently queried columns
-   Caching strategy using Moodle's cache API
-   Pagination for large datasets
-   Batch operations for bulk updates
-   CDN for static assets

### Scalability Targets

-   Support 100+ simultaneous users per board
-   Handle 1000+ cards per board
-   Page load time < 2 seconds
-   Real-time update latency < 500ms

----------

## Security Considerations

1.  **Input Validation**: All user input sanitised
2.  **XSS Prevention**: Content Security Policy headers
3.  **CSRF Protection**: Moodle's built-in sesskey validation
4.  **File Upload**: Type validation, size limits, virus scanning
5.  **SQL Injection**: Parameterised queries only
6.  **Rate Limiting**: Prevent spam and DoS
7.  **Privacy**: GDPR compliance, data retention policies

----------

## Testing Strategy

### Unit Tests

-   PHPUnit for backend services
-   Jest for Vue components
-   80% code coverage target

### Integration Tests

-   Behat for user workflows
-   API endpoint testing
-   Cross-browser testing

### Performance Tests

-   Load testing with JMeter
-   Lighthouse audits
-   Mobile performance testing

### Accessibility Tests

-   WAVE tool validation
-   Screen reader testing
-   Keyboard navigation testing

----------

## Documentation Requirements

1.  **User Documentation**
    
    -   Teacher guide
    -   Student guide
    -   Video tutorials
    -   Template gallery
2.  **Technical Documentation**
    
    -   API documentation
    -   Plugin installation guide
    -   Customisation guide
    -   Troubleshooting guide
3.  **Developer Documentation**
    
    -   Code comments
    -   Architecture decisions
    -   Contributing guidelines
    -   Plugin hooks/events

----------

## Success Metrics

### Adoption Metrics

-   100+ installations in first 6 months
-   1000+ active boards created
-   4.5+ star rating on Moodle plugins directory

### Performance Metrics

-   99.9% uptime
-   < 2s average page load
-   < 500ms real-time update latency

### User Satisfaction

-   Net Promoter Score > 50
-   Support ticket resolution < 24 hours
-   Feature request implementation cycle < 3 months

----------

## License & Distribution

-   **License**: GPL v3 (Moodle compatible)
-   **Distribution**: Moodle Plugins Directory
-   **Repository**: GitHub (public)
-   **Support**: Community forums + paid support option

----------

## Budget & Resources

### Development Team

-   1 Lead Developer (Vue.js/PHP expert)
-   1 Frontend Developer (Vue.js specialist)
-   1 UI/UX Designer (part-time)
-   1 QA Tester (part-time)

### Infrastructure

-   Development server
-   Testing environments
-   CI/CD pipeline (GitHub Actions)
-   Demo site hosting

----------

## Next Steps

1.  **Finalise plugin name**  and register namespace
2.  **Set up development environment**
3.  **Create Git repository**  and project structure
4.  **Implement basic database**  schema
5.  **Set up Vue.js with Webpack**  configuration
6.  **Build first working prototype**  (Wall layout with text cards)
7.  **Establish CI/CD pipeline**
8.  **Begin iterative development**  following phases

----------

## Contact & Contributors

**Project Lead**: David Kelly  **Repository**: github.com/davidjaykelly/moodle-mod_mosaic  **Documentation**: davidkel.ly/mosaic  **Support**: contact@davidkel.ly

----------

_This specification is a living document and will be updated as the project evolves._

_Last Updated: [Current Date]_  _Version: 1.0.0_
