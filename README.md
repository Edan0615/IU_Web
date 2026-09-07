# 🧠 NOW — Master the Present Moment with Jungian Cognitive Rotation

> **NOW** is an advanced AI-powered cognitive counseling platform built on **Carl Jung's 8-Cognitive Functions Theory** (Ni, Ne, Si, Se, Ti, Te, Fi, Fe) and the **Two-Stage Cognitive Rotation Engine**.
> It helps users escape internal self-blame loops and logic analysis paralysis by dynamically guiding their cognitive mindset back into present-moment clarity and actionable execution.

---

## 🌟 Key Features & Architecture

### 🧠 Carl Jung 8-Cognitive Functions Framework
NOW measures and balances 8 cognitive orientation dimensions during every statement:
- **Fi (Introverted Feeling)**: Core personal values & authentic emotional alignment.
- **Fe (Extraverted Feeling)**: Active social empathy & interpersonal harmony.
- **Ti (Introverted Thinking)**: Deep internal logical analysis & conceptual frameworks.
- **Te (Extraverted Thinking)**: Structured task execution & organized action.
- **Ni (Introverted Intuition)**: Singular long-term vision & deep pattern synthesis.
- **Ne (Extraverted Intuition)**: Exploring fresh creative possibilities & open options.
- **Si (Introverted Sensing)**: Anchoring steady routines & experiential memory.
- **Se (Extraverted Sensing)**: Present sensory awareness & physical reality.

---

### ⚙️ Two-Stage Cognitive Rotation Engine
When emotional stress or cognitive traps occur, NOW detects your state and rotates your attention through a precise 2-stage psychological intervention pipeline:

1. **Phase 0: State Diagnosis (Loop Detection)**
   - Real-time LLM semantic analysis identifies self-blame traps (Fi), logic analysis paralysis (Ti), catastrophic future anxiety (Ni), or past failure memory loops (Si).
2. **Stage 1 Rotation: Bridge Function Pivot (Soothe & Support)**
   - Deploys auxiliary support—active empathy (Fe), creative possibilities (Ne), or inner courage affirmation (Fi)—to de-escalate emotional paralysis and soothe anxiety.
3. **Stage 2 Rotation: Target Function Grounding (Action in NOW)**
   - Directs your cognitive focus straight into present execution (Te), physical sensory reality (Se), or clear goal vision (Ni) in the present moment (**"NOW"**).

#### 🔄 Dynamic Rotation Vector Examples
- **Self-Blame Trap**: `Fi → Fe (Bridge) → Te (Target)`
- **Logic Over-Analysis**: `Ti → Ne (Bridge) → Se (Target)`
- **Catastrophic Anxiety**: `Ni → Fi (Bridge) → Se (Target)`
- **Past Failure Memory**: `Si → Fe (Bridge) → Ne (Target)`

---

### 🖥️ Full-Screen Responsive Counseling Workspace
- **Full Viewport App Layout (`vh-100` / `100dvh`)**: Dynamic flex height chat interface that automatically adapts to any laptop, desktop monitor, or mobile device.
- **Per-Message Spectrum Radar**: Interactive Chart.js radar charts and reasoning rationales (`cognitive_reasoning`) embedded per response with collapsible toggles.
- **Session History & One-Click Restoration**: Registered members can browse past counseling sessions on their Dashboard (`/home`) and instantly jump back into any session with all history and cognitive spectrums preserved.
- **Guest Free Trial Gate**: 3-message free trial limit for unauthenticated guest users with automated HTTP 403 server-side enforcement.

---

## 🛠️ Technology Stack

| Layer | Technologies Used |
| :--- | :--- |
| **Backend Framework** | Laravel 10 (PHP 8.2+), Eloquent ORM, Web Session Middleware |
| **AI Engine / LLM** | Groq Service API, JSON Semantic Parser, Fallback Spectrum Engine |
| **Frontend UI** | Vue 3 Composition API (`<script setup>`), Bootstrap 5, SCSS |
| **Data Visualization** | Chart.js, `vue-chartjs` |
| **Asset Bundling** | Vite (Hot Module Replacement HMR) |
| **Test Suite** | PHPUnit (21 Unit & Feature Tests, 131 Assertions) |

---

## 🚀 Quick Start Guide

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js (v18+ recommended) & npm
- SQLite / MySQL

### Installation

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/Edan0615/IU_Web.git
   cd IU-Web-Project
   ```

2. **Install Backend & Frontend Dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment File**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run Database Migrations & Seeders**:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Build Assets & Start Local Server**:
   ```bash
   # Terminal 1: Compile Vite Assets
   npm run dev

   # Terminal 2: Start Laravel Local Server
   php artisan serve
   ```

---

## 🔑 Demo Account Credentials

For testing and demonstration, use the seeded test account:
- **URL**: `http://127.0.0.1:8000/login`
- **Email**: `tester@gmail.com`
- **Password**: `abc123456789`

---

## 🧪 Automated Testing Suite

Run the full PHPUnit test suite covering unit calculations, LLM response parsing, edge cases, XSS script injection, long inputs, and 3-message guest trial enforcement:

```bash
php artisan test
```

*Note: After running tests, re-seed the database with `php artisan db:seed` to ensure demo data remains ready.*

---

## 📄 License
This project is open-sourced under the [MIT License](LICENSE).
