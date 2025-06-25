# DiveForge: Enterprise Dive Shop Management Application
## Complete Technical Developer Guide

**Version 30** | **GPL v3 Licensed** | **Multi-Agency Universal Platform**

---

DiveForge is an open source enterprise application designed for universal dive shop management.  
All code, architecture, and features are developed and maintained under the GPL v3 license to ensure community ownership, transparency, and extensibility.

## Table of Contents

1. Executive Summary
2. Open Source Development Foundation
3. Modular Architecture Design
4. Web-Based Installation System
5. Multi-Agency Certification System
6. DiveShop360.biz API Compatibility Layer
7. Enterprise Application Architecture
8. Database Architecture and Management
9. Security Framework and Compliance
10. Customer Portal Development
11. Theme and Branding Systems
12. Logging and Monitoring Systems
13. Implementation Roadmap

---

## Executive Summary

DiveForge is the first truly universal dive shop management platform designed to support all major diving certification agencies through a unified, open source solution. Built under GPL v3 licensing, DiveForge provides enterprise-grade capabilities while respecting user freedoms and encouraging community collaboration.

### Core Mission
Break down artificial barriers between diving certification agencies while providing dive shops with unprecedented flexibility, comprehensive functionality, and enterprise-grade security.

### Key Differentiators
- Universal Agency Support: PADI, SSI, TDI/SDI, NAUI, BSAC, GUE, IANTD, RAID, and 20+ regional agencies
- GPL v3 Freedom: Community ownership preventing vendor lock-in
- Enterprise Security: PCI DSS and GDPR compliance with comprehensive audit trails
- Migration Ready: Seamless transition from DiveShop360.biz and other existing systems
- Plugin Architecture: Community-driven extensibility

---

## Open Source Development Foundation

DiveForge is governed by a community-driven model, with a Project Steering Committee, Community Council, and Working Groups. All contributors have a voice in the direction and development of the platform.

### License Benefits
- User Freedom: Four essential freedoms guaranteed
- Patent Protection: Robust patent grants protecting against litigation
- Anti-Tivoization: Hardware restrictions prevention
- Community Ownership: Democratic governance and development

---

## Modular Architecture Design

DiveForge uses a modular, plugin-based architecture to ensure extensibility, maintainability, and scalability for enterprise deployments. All modules and plugins are GPL v3 licensed.

---

## Web-Based Installation System

DiveForge provides a user-friendly, web-based installation wizard for easy setup and configuration, supporting multiple database systems and enterprise-grade security options.

---

## Multi-Agency Certification System

DiveForge supports all major diving certification agencies through a unified, standards-based integration framework, enabling seamless management of certifications, instructors, and customers.

---

## DiveShop360.biz API Compatibility Layer

DiveForge includes a compatibility layer for smooth migration from DiveShop360.biz and other legacy systems, ensuring data integrity and business continuity.

---

## Enterprise Application Architecture

DiveForge is designed as a modular monolith for most deployments, with support for microservices and hybrid architectures as needed. The platform emphasizes data consistency, security, and operational simplicity.

---

## Database Architecture and Management

DiveForge supports multiple database backends (PostgreSQL, MySQL/MariaDB, SQLite, SQL Server) and provides robust schema management, migrations, and backup/restore capabilities. Enterprise deployments benefit from high-availability options and automated maintenance tools.

---

## Security Framework and Compliance

DiveForge implements enterprise-grade security, including role-based access control, multi-factor authentication, encryption at rest and in transit, and comprehensive audit logging. The platform is designed to meet PCI DSS and GDPR requirements, with regular security reviews and community oversight.

---

## Customer Portal Development

DiveForge offers a customizable customer portal, enabling self-service access to certifications, bookings, payments, and communication. The portal is mobile-friendly, supports multiple languages, and can be branded to match the dive shop's identity.

---

## Theme and Branding Systems

DiveForge includes a flexible theming and branding system, allowing dive shops to customize the look and feel of both the admin dashboard and customer portal. Themes can be extended or created by the community, ensuring a unique and professional appearance.

---

## Logging and Monitoring Systems

DiveForge provides integrated logging, monitoring, and alerting features for operational transparency and rapid troubleshooting. Logs can be exported to enterprise SIEM solutions, and built-in dashboards provide real-time insights into system health, user activity, and security events. Automated alerts can be configured for critical incidents.

---

## Implementation Roadmap

The DiveForge roadmap is managed openly with community input. Major milestones include expanded agency integrations, advanced reporting and analytics, e-commerce features, enhanced mobile support, and continuous security improvements. Contributions are welcome from all qualified developers and dive industry professionals.

---

*For more information, visit the official DiveForge repository and documentation.*
- **PATCH**: Bug fixes and minor improvements

#### **Release Management**
```yaml
# .github/workflows/release.yml
name: Release Management
on:
  push:
    tags: ['v*']

jobs:
  release:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Generate Changelog
        run: conventional-changelog -p angular -i CHANGELOG.md -s
      - name: Create Release
        uses: actions/create-release@v1
        with:
          tag_name: ${{ github.ref }}
          release_name: DiveForge ${{ github.ref }}
          body_path: CHANGELOG.md
```

---

## Modular Architecture Design

### **Plugin-Based System Architecture**

#### **Core Plugin Interface**
```typescript
interface DiveForgePlugin {
    name: string;
    version: string;
    dependencies: string[];
    
    initialize(context: PluginContext): Promise<void>;
    shutdown(): Promise<void>;
    getCapabilities(): PluginCapability[];
    getMetadata(): PluginMetadata;
}

interface PluginContext {
    database: DatabaseConnection;
    eventBus: EventBus;
    logger: Logger;
    configuration: ConfigurationManager;
    security: SecurityContext;
}

interface PluginCapability {
    type: 'agency-integration' | 'equipment-provider' | 'payment-processor' | 'reporting' | 'ui-component';
    scope: string[];
    permissions: Permission[];
}
```

#### **Plugin Registry Implementation**
```typescript
class PluginRegistry {
    private plugins: Map<string, DiveForgePlugin> = new Map();
    private dependencies: Map<string, string[]> = new Map();
    private capabilities: Map<string, PluginCapability[]> = new Map();
    
    async loadPlugin(pluginPath: string): Promise<void> {
        const plugin = await this.validateAndLoad(pluginPath);
        await this.resolveDependencies(plugin);
        await plugin.initialize(this.createContext(plugin));
        
        this.plugins.set(plugin.name, plugin);
        this.capabilities.set(plugin.name, plugin.getCapabilities());
        
        this.eventBus.emit('plugin:loaded', { plugin: plugin.name });
    }
    
    async validateAndLoad(pluginPath: string): Promise<DiveForgePlugin> {
        const plugin = await import(pluginPath);
        
        // GPL v3 license validation
        if (!plugin.license || plugin.license !== 'GPL-3.0') {
            throw new Error(`Plugin must be GPL v3 licensed: ${pluginPath}`);
        }
        
        // Security validation
        await this.securityValidator.validatePlugin(plugin);
        
        return plugin;
    }
    
    async resolveDependencies(plugin: DiveForgePlugin): Promise<void> {
        for (const dependency of plugin.dependencies) {
            if (!this.plugins.has(dependency)) {
                throw new Error(`Missing dependency: ${dependency} for plugin: ${plugin.name}`);
            }
        }
    }
}
```

### **Recommended Module Structure**

#### **Core Modules**
```
DiveForge/
├── 🔧 core/
│   ├── engine/                 # Universal certification processing
│   ├── database/               # Multi-database abstraction layer
│   ├── security/               # Authentication & authorization
│   ├── api/                    # RESTful API framework
│   └── events/                 # Event-driven architecture
├── 🏢 agencies/
│   ├── padi/                   # PADI integration adapter
│   ├── ssi/                    # SSI digital-first integration
│   ├── tdi/                    # TDI technical diving support
│   ├── naui/                   # NAUI flexible standards
│   ├── bsac/                   # BSAC club diving integration
│   ├── gue/                    # GUE team diving protocols
│   ├── iantd/                  # IANTD technical certifications
│   ├── raid/                   # RAID rebreather specialization
│   └── universal/              # Cross-agency compatibility
├── 💾 data/
│   ├── models/                 # Universal data models
│   ├── migrations/             # Database schema management
│   ├── repositories/           # Data access patterns
│   └── validators/             # Data integrity enforcement
├── 🌐 interfaces/
│   ├── web/                    # Progressive web application
│   ├── api/                    # API endpoints and documentation
│   ├── mobile/                 # Mobile-specific optimizations
│   └── portal/                 # Customer self-service portal
├── 🔌 plugins/
│   ├── equipment/              # Equipment management extensions
│   ├── reporting/              # Advanced analytics and reports
│   ├── integrations/           # Third-party service integrations
│   └── themes/                 # UI themes and branding
├── 🛡️ security/
│   ├── authentication/         # OAuth 2.0/OpenID Connect
│   ├── authorization/          # Role-based access control
│   ├── encryption/             # Data protection mechanisms
│   └── audit/                  # Comprehensive audit logging
└── 📊 monitoring/
    ├── logging/                # Structured logging system
    ├── metrics/                # Performance monitoring
    ├── alerts/                 # Automated alerting system
    └── health/                 # System health checks
```

#### **Module Dependencies**
```typescript
// Module dependency mapping
const moduleDependencies = {
    'agencies/padi': ['core/engine', 'core/database', 'core/security'],
    'agencies/ssi': ['core/engine', 'core/database', 'core/security'],
    'agencies/tdi': ['core/engine', 'core/database', 'core/security'],
    'interfaces/web': ['core/api', 'core/security', 'agencies/*'],
    'interfaces/portal': ['core/api', 'core/security', 'data/models'],
    'plugins/equipment': ['core/engine', 'data/models'],
    'plugins/reporting': ['core/database', 'data/repositories']
};
```

---

## Web-Based Installation System

### **Installation Wizard Architecture**

#### **Core Installation Framework**
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DiveForge Installation Wizard</title>
    <link rel="stylesheet" href="/assets/css/installer.css">
    <link rel="icon" href="/assets/img/diveforge-favicon.ico">
</head>
<body>
    <div class="installer-container">
        <header class="installer-header">
            <div class="logo-section">
                <img src="/assets/img/diveforge-logo.svg" alt="DiveForge" class="logo">
                <h1>DiveForge Installation</h1>
                <span class="version">v1.0.0</span>
            </div>
            <div class="installation-progress">
                <div class="progress-bar">
                    <div class="progress-fill" id="progress-fill"></div>
                </div>
                <span class="progress-text" id="progress-text">Step 1 of 6</span>
            </div>
        </header>
        
        <nav class="step-navigation">
            <div class="step active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-title">Welcome</div>
            </div>
            <div class="step" data-step="2">
                <div class="step-number">2</div>
                <div class="step-title">Database</div>
            </div>
            <div class="step" data-step="3">
                <div class="step-number">3</div>
                <div class="step-title">Admin Account</div>
            </div>
            <div class="step" data-step="4">
                <div class="step-number">4</div>
                <div class="step-title">Shop Setup</div>
            </div>
            <div class="step" data-step="5">
                <div class="step-number">5</div>
                <div class="step-title">Migration</div>
            </div>
            <div class="step" data-step="6">
                <div class="step-number">6</div>
                <div class="step-title">Complete</div>
            </div>
        </nav>
        
        <main class="installer-content">
            <div id="step-content">
                <!-- Dynamic step content loaded here -->
            </div>
            
            <div class="installer-actions">
                <button type="button" id="btn-previous" class="btn btn-secondary" disabled>
                    ← Previous
                </button>
                <button type="button" id="btn-next" class="btn btn-primary">
                    Next →
                </button>
            </div>
        </main>
        
        <footer class="installer-footer">
            <div class="footer-content">
                <p>&copy; 2025 DiveForge Community | Licensed under GPL v3</p>
                <div class="footer-links">
                    <a href="https://diveforge.org/docs" target="_blank">Documentation</a>
                    <a href="https://github.com/diveforge/diveforge" target="_blank">Source Code</a>
                    <a href="https://diveforge.org/support" target="_blank">Support</a>
                </div>
            </div>
        </footer>
    </div>
    
    <script src="/assets/js/installer.js"></script>
</body>
</html>
```

#### **Installation Wizard JavaScript Framework**
```javascript
class DiveForgeInstaller {
    constructor() {
        this.currentStep = 1;
        this.maxSteps = 6;
        this.config = {
            database: {},
            admin: {},
            shop: {},
            agencies: [],
            migration: {},
            security: {}
        };
        this.validationRules = new Map();
        this.stepHandlers = new Map();
        
        this.initializeEventListeners();
        this.setupValidationRules();
        this.setupStepHandlers();
    }
    
    initializeEventListeners() {
        document.getElementById('btn-next').addEventListener('click', () => this.nextStep());
        document.getElementById('btn-previous').addEventListener('click', () => this.previousStep());
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.nextStep();
            }
        });
    }
    
    async nextStep() {
        if (await this.validateCurrentStep()) {
            if (this.currentStep < this.maxSteps) {
                this.currentStep++;
                await this.renderStep();
                this.updateProgress();
            } else {
                await this.completeInstallation();
            }
        }
    }
    
    async previousStep() {
        if (this.currentStep > 1) {
            this.currentStep--;
            await this.renderStep();
            this.updateProgress();
        }
    }
    
    async validateCurrentStep() {
        const validator = this.validationRules.get(this.currentStep);
        if (!validator) return true;
        
        try {
            const result = await validator(this.config);
            if (!result.valid) {
                this.displayErrors(result.errors);
                return false;
            }
            this.clearErrors();
            return true;
        } catch (error) {
            this.displayErrors([`Validation error: ${error.message}`]);
            return false;
        }
    }
    
    async renderStep() {
        const stepContent = await this.loadStepContent(this.currentStep);
        document.getElementById('step-content').innerHTML = stepContent;
        
        // Initialize step-specific functionality
        const handler = this.stepHandlers.get(this.currentStep);
        if (handler) {
            await handler.initialize();
        }
        
        this.updateNavigation();
    }
    
    updateProgress() {
        const progressPercent = ((this.currentStep - 1) / (this.maxSteps - 1)) * 100;
        document.getElementById('progress-fill').style.width = `${progressPercent}%`;
        document.getElementById('progress-text').textContent = `Step ${this.currentStep} of ${this.maxSteps}`;
        
        // Update step navigation
        document.querySelectorAll('.step').forEach((step, index) => {
            step.classList.toggle('active', index + 1 === this.currentStep);
            step.classList.toggle('completed', index + 1 < this.currentStep);
        });
    }
    
    displayErrors(errors) {
        const errorContainer = document.getElementById('error-container') || this.createErrorContainer();
        errorContainer.innerHTML = `
            <div class="alert alert-error">
                <h4>Please correct the following errors:</h4>
                <ul>
                    ${errors.map(error => `<li>${error}</li>`).join('')}
                </ul>
            </div>
        `;
        errorContainer.scrollIntoView({ behavior: 'smooth' });
    }
    
    clearErrors() {
        const errorContainer = document.getElementById('error-container');
        if (errorContainer) {
            errorContainer.innerHTML = '';
        }
    }
}
```

### **Step 1: Welcome and System Requirements**

#### **Welcome Step Implementation**
```javascript
const welcomeStep = {
    template: `
    <div class="step-content welcome-step">
        <div class="welcome-header">
            <h2>Welcome to DiveForge</h2>
            <p class="welcome-subtitle">The Universal Open Source Dive Shop Management Platform</p>
        </div>
        
        <div class="feature-highlights">
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon">🌊</div>
                    <h3>Universal Agency Support</h3>
                    <p>Support for PADI, SSI, TDI, NAUI, BSAC, GUE, IANTD, RAID, and 20+ agencies</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔓</div>
                    <h3>Open Source Freedom</h3>
                    <p>GPL v3 licensed ensuring community ownership and collaboration</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🏢</div>
                    <h3>Enterprise Ready</h3>
                    <p>PCI DSS compliant with comprehensive security and audit capabilities</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔄</div>
                    <h3>Easy Migration</h3>
                    <p>Seamless transition from DiveShop360.biz and other existing systems</p>
                </div>
            </div>
        </div>
        
        <div class="system-requirements">
            <h3>System Requirements Check</h3>
            <div class="requirements-grid" id="requirements-grid">
                <!-- Populated dynamically -->
            </div>
        </div>
        
        <div class="installation-options">
            <h3>Installation Type</h3>
            <div class="option-cards">
                <label class="option-card">
                    <input type="radio" name="installation-type" value="new" checked>
                    <div class="option-content">
                        <h4>New Installation</h4>
                        <p>Fresh DiveForge installation with initial configuration</p>
                    </div>
                </label>
                <label class="option-card">
                    <input type="radio" name="installation-type" value="migrate">
                    <div class="option-content">
                        <h4>Migration from Existing System</h4>
                        <p>Import data from DiveShop360.biz or other dive shop software</p>
                    </div>
                </label>
            </div>
        </div>
        
        <div class="license-agreement">
            <h3>License Agreement</h3>
            <div class="license-text">
                <p>DiveForge is licensed under the GNU General Public License v3.0 (GPL v3). 
                This ensures your freedom to use, study, modify, and distribute this software.</p>
                <label class="license-checkbox">
                    <input type="checkbox" id="license-accepted" required>
                    I accept the GPL v3 license terms and understand my rights and obligations
                </label>
            </div>
        </div>
    </div>`,
    
    async initialize() {
        await this.checkSystemRequirements();
        this.setupInstallationTypeHandlers();
    },
    
    async checkSystemRequirements() {
        const requirements = [
            { name: 'PHP Version', check: 'php_version', minimum: '8.1' },
            { name: 'Node.js', check: 'node_version', minimum: '18.0' },
            { name: 'Database Support', check: 'database_support', required: true },
            { name: 'Web Server', check: 'web_server', required: true },
            { name: 'SSL Certificate', check: 'ssl_support', recommended: true },
            { name: 'Memory Limit', check: 'memory_limit', minimum: '256M' },
            { name: 'Disk Space', check: 'disk_space', minimum: '1GB' }
        ];
        
        const grid = document.getElementById('requirements-grid');
        
        for (const req of requirements) {
            const result = await this.checkRequirement(req);
            const statusClass = result.status === 'pass' ? 'success' : 
                               result.status === 'warning' ? 'warning' : 'error';
            
            const reqElement = document.createElement('div');
            reqElement.className = `requirement-item ${statusClass}`;
            reqElement.innerHTML = `
                <div class="req-name">${req.name}</div>
                <div class="req-status">
                    <span class="status-icon">${this.getStatusIcon(result.status)}</span>
                    <span class="status-text">${result.message}</span>
                </div>
            `;
            grid.appendChild(reqElement);
        }
    },
    
    async checkRequirement(requirement) {
        try {
            const response = await fetch('/installer/check-requirement', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(requirement)
            });
            return await response.json();
        } catch (error) {
            return {
                status: 'error',
                message: `Check failed: ${error.message}`
            };
        }
    }
};
```

### **Step 2: Database Configuration**

#### **Multi-Database Support Interface**
```javascript
const databaseStep = {
    template: `
    <div class="step-content database-step">
        <div class="step-header">
            <h2>Database Configuration</h2>
            <p>Choose and configure your preferred database system</p>
        </div>
        
        <div class="database-selection">
            <h3>Select Database Type</h3>
            <div class="database-grid">
                <label class="database-option" data-type="postgresql">
                    <input type="radio" name="database-type" value="postgresql">
                    <div class="db-card">
                        <img src="/assets/img/postgresql-logo.svg" alt="PostgreSQL" class="db-logo">
                        <h4>PostgreSQL</h4>
                        <p class="db-description">Recommended for production environments</p>
                        <div class="db-features">
                            <span class="feature-tag">ACID Compliant</span>
                            <span class="feature-tag">JSON Support</span>
                            <span class="feature-tag">Scalable</span>
                        </div>
                    </div>
                </label>
                
                <label class="database-option" data-type="mysql">
                    <input type="radio" name="database-type" value="mysql">
                    <div class="db-card">
                        <img src="/assets/img/mysql-logo.svg" alt="MySQL" class="db-logo">
                        <h4>MySQL/MariaDB</h4>
                        <p class="db-description">Popular choice with excellent performance</p>
                        <div class="db-features">
                            <span class="feature-tag">High Performance</span>
                            <span class="feature-tag">Wide Compatibility</span>
                            <span class="feature-tag">Mature</span>
                        </div>
                    </div>
                </label>
                
                <label class="database-option" data-type="sqlite">
                    <input type="radio" name="database-type" value="sqlite">
                    <div class="db-card">
                        <img src="/assets/img/sqlite-logo.svg" alt="SQLite" class="db-logo">
                        <h4>SQLite</h4>
                        <p class="db-description">Perfect for small shops and testing</p>
                        <div class="db-features">
                            <span class="feature-tag">No Setup Required</span>
                            <span class="feature-tag">Lightweight</span>
                            <span class="feature-tag">Self-Contained</span>
                        </div>
                    </div>
                </label>
                
                <label class="database-option" data-type="sqlserver">
                    <input type="radio" name="database-type" value="sqlserver">
                    <div class="db-card">
                        <img src="/assets/img/sqlserver-logo.svg" alt="SQL Server" class="db-logo">
                        <h4>SQL Server</h4>
                        <p class="db-description">Enterprise Microsoft environments</p>
                        <div class="db-features">
                            <span class="feature-tag">Enterprise Features</span>
                            <span class="feature-tag">Azure Integration</span>
                            <span class="feature-tag">Business Intelligence</span>
                        </div>
                    </div>
                </label>
            </div>
        </div>
        
        <div id="database-config-form" class="database-config" style="display: none;">
            <h3>Database Connection Details</h3>
            <form id="db-connection-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="db-host">Database Host:</label>
                        <input type="text" id="db-host" name="host" value="localhost" required>
                        <small class="field-help">IP address or hostname of your database server</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="db-port">Port:</label>
                        <input type="number" id="db-port" name="port" required>
                        <small class="field-help">Database server port number</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="db-name">Database Name:</label>
                        <input type="text" id="db-name" name="database" value="diveforge" required>
                        <small class="field-help">Name for the DiveForge database</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="db-username">Username:</label>
                        <input type="text" id="db-username" name="username" required>
                        <small class="field-help">Database user with create/modify permissions</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="db-password">Password:</label>
                        <input type="password" id="db-password" name="password" required>
                        <small class="field-help">Password for the database user</small>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="db-charset">Character Set:</label>
                        <select id="db-charset" name="charset">
                            <option value="utf8mb4">utf8mb4 (Recommended)</option>
                            <option value="utf8">utf8</option>
                        </select>
                        <small class="field-help">Character encoding for international support</small>
                    </div>
                </div>
                
                <div class="connection-actions">
                    <button type="button" id="test-connection" class="btn btn-secondary">
                        <span class="btn-icon">🔗</span>
                        Test Connection
                    </button>
                    <button type="button" id="create-database" class="btn btn-secondary" disabled>
                        <span class="btn-icon">🗄️</span>
                        Create Database
                    </button>
                </div>
                
                <div id="connection-status" class="connection-status"></div>
            </form>
        </div>
        
        <div class="advanced-options">
            <details>
                <summary>Advanced Database Options</summary>
                <div class="advanced-form">
                    <div class="form-group">
                        <label for="db-pool-size">Connection Pool Size:</label>
                        <input type="number" id="db-pool-size" name="poolSize" value="10" min="1" max="100">
                    </div>
                    <div class="form-group">
                        <label for="db-timeout">Connection Timeout (seconds):</label>
                        <input type="number" id="db-timeout" name="timeout" value="30" min="5" max="300">
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="sslEnabled" checked>
                            Enable SSL/TLS encryption
                        </label>
                    </div>
                </div>
            </details>
        </div>
    </div>`,
    
    async initialize() {
        this.setupDatabaseTypeHandlers();
        this.setupConnectionTesting();
    },
    
    setupDatabaseTypeHandlers() {
        const typeInputs = document.querySelectorAll('input[name="database-type"]');
        const configForm = document.getElementById('database-config-form');
        
        typeInputs.forEach(input => {
            input.addEventListener('change', (e) => {
                const dbType = e.target.value;
                this.updatePortForDatabase(dbType);
                configForm.style.display = 'block';
                this.updateFormForDatabase(dbType);
            });
        });
    },
    
    updatePortForDatabase(dbType) {
        const portDefaults = {
            postgresql: 5432,
            mysql: 3306,
            sqlite: null,
            sqlserver: 1433
        };
        
        const portInput = document.getElementById('db-port');
        if (dbType === 'sqlite') {
            portInput.closest('.form-group').style.display = 'none';
        } else {
            portInput.closest('.form-group').style.display = 'block';
            portInput.value = portDefaults[dbType];
        }
    },
    
    async testConnection() {
        const formData = new FormData(document.getElementById('db-connection-form'));
        const config = Object.fromEntries(formData.entries());
        
        try {
            const response = await fetch('/installer/test-database', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(config)
            });
            
            const result = await response.json();
            this.displayConnectionStatus(result);
            
            if (result.success) {
                document.getElementById('create-database').disabled = false;
            }
            
            return result;
        } catch (error) {
            this.displayConnectionStatus({
                success: false,
                message: `Connection test failed: ${error.message}`
            });
            return { success: false };
        }
    }
};
```

### **Step 3: Administrator Account Creation**

#### **Admin Setup with Multi-Agency Support**
```javascript
const adminStep = {
    template: `
    <div class="step-content admin-step">
        <div class="step-header">
            <h2>Create Administrator Account</h2>
            <p>Set up the primary administrator for your DiveForge installation</p>
        </div>
        
        <form id="admin-form" class="admin-form">
            <div class="personal-info-section">
                <h3>Personal Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="admin-first-name">First Name:</label>
                        <input type="text" id="admin-first-name" name="firstName" required>
                    </div>
                    <div class="form-group">
                        <label for="admin-last-name">Last Name:</label>
                        <input type="text" id="admin-last-name" name="lastName" required>
                    </div>
                    <div class="form-group">
                        <label for="admin-email">Email Address:</label>
                        <input type="email" id="admin-email" name="email" required>
                        <small class="field-help">This will be your login username</small>
                    </div>
                    <div class="form-group">
                        <label for="admin-phone">Phone Number:</label>
                        <input type="tel" id="admin-phone" name="phone">
                    </div>
                </div>
            </div>
            
            <div class="security-section">
                <h3>Security Settings</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="admin-password">Password:</label>
                        <input type="password" id="admin-password" name="password" required minlength="12">
                        <div class="password-strength" id="password-strength"></div>
                        <small class="field-help">Minimum 12 characters with mixed case, numbers, and symbols</small>
                    </div>
                    <div class="form-group">
                        <label for="admin-password-confirm">Confirm Password:</label>
                        <input type="password" id="admin-password-confirm" name="passwordConfirm" required>
                    </div>
                </div>
                
                <div class="mfa-setup">
                    <h4>Multi-Factor Authentication</h4>
                    <div class="mfa-options">
                        <label class="mfa-option">
                            <input type="radio" name="mfaType" value="totp" checked>
                            <div class="option-content">
                                <strong>Authenticator App (Recommended)</strong>
                                <p>Use Google Authenticator, Authy, or similar TOTP app</p>
                            </div>
                        </label>
                        <label class="mfa-option">
                            <input type="radio" name="mfaType" value="sms">
                            <div class="option-content">
                                <strong>SMS Code</strong>
                                <p>Receive codes via text message</p>
                            </div>
                        </label>
                        <label class="mfa-option">
                            <input type="radio" name="mfaType" value="email">
                            <div class="option-content">
                                <strong>Email Code</strong>
                                <p>Receive codes via email (less secure)</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="instructor-credentials-section">
                <h3>Instructor Credentials (Optional)</h3>
                <p class="section-description">Add your instructor certifications from various agencies</p>
                
                <div class="agency-credentials">
                    <div class="agency-group">
                        <h4>PADI Credentials</h4>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="padi-number">PADI Number:</label>
                                <input type="text" id="padi-number" name="padiNumber" placeholder="12345">
                            </div>
                            <div class="form-group">
                                <label for="padi-level">Instructor Level:</label>
                                <select id="padi-level" name="padiLevel">
                                    <option value="">Select Level</option>
                                    <option value="ASSISTANT_INSTRUCTOR">Assistant Instructor</option>
                                    <option value="OPEN_WATER_INSTRUCTOR">Open Water Instructor</option>
                                    <option value="SPECIALTY_INSTRUCTOR">Specialty Instructor</option>
                                    <option value="MASTER_INSTRUCTOR">Master Instructor</option>
                                    <option value="COURSE_DIRECTOR">Course Director</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="agency-group">
                        <h4>SSI Credentials</h4>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="ssi-number">SSI Number:</label>
                                <input type="text" id="ssi-number" name="ssiNumber" placeholder="TC12345">
                            </div>
                            <div class="form-group">
                                <label for="ssi-level">Instructor Level:</label>
                                <select id="ssi-level" name="ssiLevel">
                                    <option value="">Select Level</option>
                                    <option value="ASSISTANT_INSTRUCTOR">Assistant Instructor</option>
                                    <option value="OPEN_WATER_INSTRUCTOR">Open Water Instructor</option>
                                    <option value="SPECIALTY_INSTRUCTOR">Specialty Instructor</option>
                                    <option value="INSTRUCTOR_TRAINER">Instructor Trainer</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="agency-group">
                        <h4>TDI/SDI Credentials</h4>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="tdi-number">TDI/SDI Number:</label>
                                <input type="text" id="tdi-number" name="tdiNumber" placeholder="I12345">
                            </div>
                            <div class="form-group">
                                <label for="tdi-level">Instructor Level:</label>
                                <select id="tdi-level" name="tdiLevel">
                                    <option value="">Select Level</option>
                                    <option value="SDI_INSTRUCTOR">SDI Instructor</option>
                                    <option value="TDI_INSTRUCTOR">TDI Instructor</option>
                                    <option value="TRIMIX_INSTRUCTOR">Trimix Instructor</option>
                                    <option value="INSTRUCTOR_TRAINER">Instructor Trainer</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="agency-group">
                        <h4>NAUI Credentials</h4>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="naui-number">NAUI Number:</label>
                                <input type="text" id="naui-number" name="nauiNumber" placeholder="F12345">
                            </div>
                            <div class="form-group">
                                <label for="naui-level">Instructor Level:</label>
                                <select id="naui-level" name="nauiLevel">
                                    <option value="">Select Level</option>
                                    <option value="ASSISTANT_INSTRUCTOR">Assistant Instructor</option>
                                    <option value="INSTRUCTOR">Instructor</option>
                                    <option value="INSTRUCTOR_TRAINER">Instructor Trainer</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-secondary add-agency-btn">
                        + Add Another Agency
                    </button>
                </div>
            </div>
            
            <div class="emergency-contact-section">
                <h3>Emergency Contact</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="emergency-name">Contact Name:</label>
                        <input type="text" id="emergency-name" name="emergencyContactName">
                    </div>
                    <div class="form-group">
                        <label for="emergency-phone">Contact Phone:</label>
                        <input type="tel" id="emergency-phone" name="emergencyContactPhone">
                    </div>
                    <div class="form-group">
                        <label for="emergency-relationship">Relationship:</label>
                        <input type="text" id="emergency-relationship" name="emergencyContactRelationship" placeholder="Spouse, Partner, Family">
                    </div>
                </div>
            </div>
        </form>
    </div>`,
    
    async initialize() {
        this.setupPasswordValidation();
        this.setupMFAHandlers();
        this.setupAgencyCredentialHandlers();
    },
    
    setupPasswordValidation() {
        const passwordInput = document.getElementById('admin-password');
        const strengthIndicator = document.getElementById('password-strength');
        
        passwordInput.addEventListener('input', (e) => {
            const strength = this.calculatePasswordStrength(e.target.value);
            this.updatePasswordStrengthDisplay(strength, strengthIndicator);
        });
    },
    
    calculatePasswordStrength(password) {
        let score = 0;
        const criteria = [
            { test: /.{12,}/, points: 2, label: 'At least 12 characters' },
            { test: /[a-z]/, points: 1, label: 'Lowercase letters' },
            { test: /[A-Z]/, points: 1, label: 'Uppercase letters' },
            { test: /[0-9]/, points: 1, label: 'Numbers' },
            { test: /[^A-Za-z0-9]/, points: 2, label: 'Special characters' },
            { test: /^(?!.*(.)\1{2,})/, points: 1, label: 'No repeated characters' }
        ];
        
        const results = criteria.map(criterion => ({
            ...criterion,
            passed: criterion.test.test(password)
        }));
        
        score = results.reduce((sum, result) => sum + (result.passed ? result.points : 0), 0);
        
        return {
            score,
            maxScore: criteria.reduce((sum, c) => sum + c.points, 0),
            results,
            level: score < 3 ? 'weak' : score < 6 ? 'medium' : 'strong'
        };
    }
};
```

### **Step 4: Enhanced Shop Setup with Multi-Agency Support**

#### **Shop Configuration Interface**
```javascript
const shopSetupStep = {
    template: `
    <div class="step-content shop-setup-step">
        <div class="step-header">
            <h2>Configure Your Dive Shop</h2>
            <p>Set up your dive shop details and select certification agencies</p>
        </div>
        
        <form id="shop-form" class="shop-form">
            <div class="basic-info-section">
                <h3>Basic Information</h3>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="shop-name">Shop Name:</label>
                        <input type="text" id="shop-name" name="name" required placeholder="Aquatic Adventures Dive Center">
                    </div>
                    
                    <div class="form-group">
                        <label for="shop-email">Business Email:</label>
                        <input type="email" id="shop-email" name="email" required placeholder="info@diveshop.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="shop-phone">Business Phone:</label>
                        <input type="tel" id="shop-phone" name="phone" placeholder="+1 (555) 123-4567">
                    </div>
                    
                    <div class="form-group">
                        <label for="shop-website">Website:</label>
                        <input type="url" id="shop-website" name="website" placeholder="https://www.diveshop.com">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="shop-address">Business Address:</label>
                    <textarea id="shop-address" name="address" rows="3" placeholder="123 Ocean Drive, Coastal City, CA 90210"></textarea>
                </div>
            </div>
            
            <div class="branding-section">
                <h3>Branding</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="shop-logo">Shop Logo:</label>
                        <input type="file" id="shop-logo" name="logo" accept="image/*">
                        <div class="logo-preview" id="logo-preview">
                            <div class="logo-placeholder">
                                <span>📸</span>
                                <p>Upload your shop logo</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="shop-colors">Brand Colors:</label>
                        <div class="color-inputs">
                            <div class="color-input-group">
                                <label for="primary-color">Primary:</label>
                                <input type="color" id="primary-color" name="primaryColor" value="#0066cc">
                            </div>
                            <div class="color-input-group">
                                <label for="secondary-color">Secondary:</label>
                                <input type="color" id="secondary-color" name="secondaryColor" value="#ffffff">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="agency-selection-section">
                <h3>Certification Agencies</h3>
                <p class="section-description">Select the diving agencies your shop works with</p>
                
                <div class="agency-grid">
                    <label class="agency-card" data-agency="PADI">
                        <input type="checkbox" name="agencies" value="PADI">
                        <div class="agency-content">
                            <img src="/assets/img/agencies/padi-logo.png" alt="PADI" class="agency-logo">
                            <h4>PADI</h4>
                            <p>Professional Association of Diving Instructors</p>
                            <div class="agency-stats">
                                <span>🌍 Worldwide</span>
                                <span>👥 6.6M+ certified</span>
                            </div>
                        </div>
                    </label>
                    
                    <label class="agency-card" data-agency="SSI">
                        <input type="checkbox" name="agencies" value="SSI">
                        <div class="agency-content">
                            <img src="/assets/img/agencies/ssi-logo.png" alt="SSI" class="agency-logo">
                            <h4>SSI</h4>
                            <p>Scuba Schools International</p>
                            <div class="agency-stats">
                                <span>💻 Digital First</span>
                                <span>👥 3M+ certified</span>
                            </div>
                        </div>
                    </label>
                    
                    <label class="agency-card" data-agency="TDI">
                        <input type="checkbox" name="agencies" value="TDI">
                        <div class="agency-content">
                            <img src="/assets/img/agencies/tdi-logo.png" alt="TDI" class="agency-logo">
                            <h4>TDI</h4>
                            <p>Technical Diving International</p>
                            <div class="agency-stats">
                                <span>⚙️ Technical</span>
                                <span>🏆 Industry Leader</span>
                            </div>
                        </div>
                    </label>
                    
                    <label class="agency-card" data-agency="NAUI">
                        <input type="checkbox" name="agencies" value="NAUI">
                        <div class="agency-content">
                            <img src="/assets/img/agencies/naui-logo.png" alt="NAUI" class="agency-logo">
                            <h4>NAUI</h4>
                            <p>National Association of Underwater Instructors</p>
                            <div class="agency-stats">
                                <span>🎯 Flexible</span>
                                <span>📚 Education Focus</span>
                            </div>
                        </div>
                    </label>
                    
                    <label class="agency-card" data-agency="BSAC">
                        <input type="checkbox" name="agencies" value="BSAC">
                        <div class="agency-content">
                            <img src="/assets/img/agencies/bsac-logo.png" alt="BSAC" class="agency-logo">
                            <h4>BSAC</h4>
                            <p>British Sub-Aqua Club</p>
                            <div class="agency-stats">
                                <span>🇬🇧 UK Based</span>
                                <span>🤝 Club Diving</span>
                            </div>
                        </div>
                    </label>
                    
                    <label class="agency-card" data-agency="GUE">
                        <input type="checkbox" name="agencies" value="GUE">
                        <div class="agency-content">
                            <img src="/assets/img/agencies/gue-logo.png" alt="GUE" class="agency-logo">
                            <h4>GUE</h4>
                            <p>Global Underwater Explorers</p>
                            <div class="agency-stats">
                                <span>👥 Team Diving</span>
                                <span>🔬 Research Focus</span>
                            </div>
                        </div>
                    </label>
                    
                    <label class="agency-card" data-agency="IANTD">
                        <input type="checkbox" name="agencies" value="IANTD">
                        <div class="agency-content">
                            <img src="/assets/img/agencies/iantd-logo.png" alt="IANTD" class="agency-logo">
                            <h4>IANTD</h4>
                            <p>International Association of Nitrox and Technical Divers</p>
                            <div class="agency-stats">
                                <span>🧪 Nitrox Pioneer</span>
                                <span>⚙️ Technical Focus</span>
                            </div>
                        </div>
                    </label>
                    
                    <label class="agency-card" data-agency="RAID">
                        <input type="checkbox" name="agencies" value="RAID">
                        <div class="agency-content">
                            <img src="/assets/img/agencies/raid-logo.png" alt="RAID" class="agency-logo">
                            <h4>RAID</h4>
                            <p>Rebreather Association of International Divers</p>
                            <div class="agency-stats">
                                <span>🔄 Rebreathers</span>
                                <span>💻 Technology</span>
                            </div>
                        </div>
                    </label>
                </div>
                
                <button type="button" class="btn btn-secondary show-more-agencies">
                    Show More Agencies (12+ additional)
                </button>
            </div>
            
            <div id="agency-specific-config" class="agency-config-section" style="display: none;">
                <h3>Agency Integration Settings</h3>
                <div id="agency-config-forms">
                    <!-- Agency-specific configuration forms will be loaded here -->
                </div>
            </div>
            
            <div class="business-settings-section">
                <h3>Business Settings</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="shop-currency">Default Currency:</label>
                        <select id="shop-currency" name="currency" required>
                            <option value="USD">USD - US Dollar</option>
                            <option value="EUR">EUR - Euro</option>
                            <option value="GBP">GBP - British Pound</option>
                            <option value="CAD">CAD - Canadian Dollar</option>
                            <option value="AUD">AUD - Australian Dollar</option>
                            <option value="MXN">MXN - Mexican Peso</option>
                            <option value="SGD">SGD - Singapore Dollar</option>
                            <option value="JPY">JPY - Japanese Yen</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="shop-timezone">Timezone:</label>
                        <select id="shop-timezone" name="timezone" required>
                            <optgroup label="North America">
                                <option value="America/New_York">Eastern Time</option>
                                <option value="America/Chicago">Central Time</option>
                                <option value="America/Denver">Mountain Time</option>
                                <option value="America/Los_Angeles">Pacific Time</option>
                                <option value="America/Anchorage">Alaska Time</option>
                                <option value="Pacific/Honolulu">Hawaii Time</option>
                            </optgroup>
                            <optgroup label="Europe">
                                <option value="Europe/London">London</option>
                                <option value="Europe/Paris">Paris</option>
                                <option value="Europe/Berlin">Berlin</option>
                                <option value="Europe/Rome">Rome</option>
                                <option value="Europe/Madrid">Madrid</option>
                            </optgroup>
                            <optgroup label="Asia Pacific">
                                <option value="Asia/Tokyo">Tokyo</option>
                                <option value="Asia/Singapore">Singapore</option>
                                <option value="Asia/Bangkok">Bangkok</option>
                                <option value="Australia/Sydney">Sydney</option>
                                <option value="Pacific/Auckland">Auckland</option>
                            </optgroup>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="shop-language">Default Language:</label>
                        <select id="shop-language" name="language" required>
                            <option value="en">English</option>
                            <option value="es">Español</option>
                            <option value="fr">Français</option>
                            <option value="de">Deutsch</option>
                            <option value="it">Italiano</option>
                            <option value="pt">Português</option>
                            <option value="ja">日本語</option>
                            <option value="zh">中文</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="operational-settings-section">
                <h3>Operational Settings</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="max-group-size">Maximum Group Size:</label>
                        <input type="number" id="max-group-size" name="maxGroupSize" value="8" min="1" max="20">
                        <small class="field-help">Default maximum students per instructor</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="booking-advance">Booking Advance (days):</label>
                        <input type="number" id="booking-advance" name="bookingAdvance" value="1" min="0" max="30">
                        <small class="field-help">Minimum days in advance for bookings</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="cancellation-policy">Cancellation Policy (hours):</label>
                        <input type="number" id="cancellation-policy" name="cancellationPolicy" value="24" min="0" max="168">
                        <small class="field-help">Hours before course for free cancellation</small>
                    </div>
                </div>
                
                <div class="operational-features">
                    <h4>Optional Features</h4>
                    <div class="feature-checkboxes">
                        <label class="feature-checkbox">
                            <input type="checkbox" name="features" value="equipment-rental" checked>
                            <span class="checkmark"></span>
                            Equipment Rental Management
                        </label>
                        <label class="feature-checkbox">
                            <input type="checkbox" name="features" value="dive-trips" checked>
                            <span class="checkmark"></span>
                            Dive Trip Organization
                        </label>
                        <label class="feature-checkbox">
                            <input type="checkbox" name="features" value="online-learning" checked>
                            <span class="checkmark"></span>
                            Online Learning Integration
                        </label>
                        <label class="feature-checkbox">
                            <input type="checkbox" name="features" value="customer-portal" checked>
                            <span class="checkmark"></span>
                            Customer Self-Service Portal
                        </label>
                        <label class="feature-checkbox">
                            <input type="checkbox" name="features" value="pos-system">
                            <span class="checkmark"></span>
                            Point of Sale System
                        </label>
                        <label class="feature-checkbox">
                            <input type="checkbox" name="features" value="inventory-management" checked>
                            <span class="checkmark"></span>
                            Inventory Management
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>`,
    
    async initialize() {
        this.setupAgencySelection();
        this.setupLogoUpload();
        this.setupColorPreviews();
        this.setupFeatureToggles();
    },
    
    setupAgencySelection() {
        const agencyCheckboxes = document.querySelectorAll('input[name="agencies"]');
        const configSection = document.getElementById('agency-specific-config');
        
        agencyCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                this.updateAgencyConfiguration();
            });
        });
    },
    
    async updateAgencyConfiguration() {
        const selectedAgencies = Array.from(document.querySelectorAll('input[name="agencies"]:checked'))
            .map(cb => cb.value);
        
        const configSection = document.getElementById('agency-specific-config');
        const configFormsContainer = document.getElementById('agency-config-forms');
        
        if (selectedAgencies.length > 0) {
            configSection.style.display = 'block';
            configFormsContainer.innerHTML = '';
            
            for (const agency of selectedAgencies) {
                const agencyConfig = await this.loadAgencyConfigForm(agency);
                configFormsContainer.appendChild(agencyConfig);
            }
        } else {
            configSection.style.display = 'none';
        }
    },
    
    async loadAgencyConfigForm(agency) {
        const agencyConfigs = {
            PADI: this.createPADIConfigForm(),
            SSI: this.createSSIConfigForm(),
            TDI: this.createTDIConfigForm(),
            NAUI: this.createNAUIConfigForm(),
            BSAC: this.createBSACConfigForm(),
            GUE: this.createGUEConfigForm(),
            IANTD: this.createIANTDConfigForm(),
            RAID: this.createRAIDConfigForm()
        };
        
        return agencyConfigs[agency] || this.createGenericConfigForm(agency);
    },
    
    createPADIConfigForm() {
        const form = document.createElement('div');
        form.className = 'agency-config-form';
        form.innerHTML = `
            <div class="agency-config-header">
                <img src="/assets/img/agencies/padi-logo.png" alt="PADI" class="config-agency-logo">
                <h4>PADI Configuration</h4>
            </div>
            <div class="config-form-grid">
                <div class="form-group">
                    <label for="padi-center-number">PADI Center Number:</label>
                    <input type="text" id="padi-center-number" name="padiCenterNumber" placeholder="S-12345">
                    <small class="field-help">Your PADI dive center number</small>
                </div>
                <div class="form-group">
                    <label for="padi-api-key">PADI API Key:</label>
                    <input type="password" id="padi-api-key" name="padiApiKey">
                    <small class="field-help">API key for PADI certification processing</small>
                </div>
                <div class="form-group">
                    <label for="padi-environment">Environment:</label>
                    <select id="padi-environment" name="padiEnvironment">
                        <option value="sandbox">Sandbox (Testing)</option>
                        <option value="production">Production</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="padiAutoSync" checked>
                        Enable automatic certification sync
                    </label>
                </div>
            </div>
        `;
        return form;
    }
};
```

### **Step 5: Migration from Existing Systems**

#### **DiveShop360.biz and Universal Migration Interface**
```javascript
const migrationStep = {
    template: `
    <div class="step-content migration-step">
        <div class="step-header">
            <h2>Data Migration</h2>
            <p>Import your existing dive shop data from previous systems</p>
        </div>
        
        <div class="migration-options">
            <h3>Migration Source</h3>
            <div class="source-grid">
                <label class="source-option" data-source="diveshop360">
                    <input type="radio" name="migration-source" value="diveshop360">
                    <div class="source-card">
                        <img src="/assets/img/systems/diveshop360-logo.png" alt="DiveShop360" class="source-logo">
                        <h4>DiveShop360.biz</h4>
                        <p>Direct API integration with automatic data mapping</p>
                        <div class="source-features">
                            <span class="feature-tag">✓ Full Integration</span>
                            <span class="feature-tag">✓ Real-time Sync</span>
                            <span class="feature-tag">✓ Zero Downtime</span>
                        </div>
                    </div>
                </label>
                
                <label class="source-option" data-source="manual">
                    <input type="radio" name="migration-source" value="manual">
                    <div class="source-card">
                        <div class="source-icon">📋</div>
                        <h4>Manual Data Entry</h4>
                        <p>Start fresh with manual customer and inventory setup</p>
                        <div class="source-features">
                            <span class="feature-tag">✓ Clean Start</span>
                            <span class="feature-tag">✓ Custom Setup</span>
                        </div>
                    </div>
                </label>
                
                <label class="source-option" data-source="csv">
                    <input type="radio" name="migration-source" value="csv">
                    <div class="source-card">
                        <div class="source-icon">📊</div>
                        <h4>CSV/Excel Import</h4>
                        <p>Import data from spreadsheets or exported files</p>
                        <div class="source-features">
                            <span class="feature-tag">✓ Flexible Format</span>
                            <span class="feature-tag">✓ Batch Import</span>
                        </div>
                    </div>
                </label>
                
                <label class="source-option" data-source="other">
                    <input type="radio" name="migration-source" value="other">
                    <div class="source-card">
                        <div class="source-icon">🔧</div>
                        <h4>Other System</h4>
                        <p>Custom migration from other dive shop software</p>
                        <div class="source-features">
                            <span class="feature-tag">✓ Custom Mapping</span>
                            <span class="feature-tag">✓ Professional Support</span>
                        </div>
                    </div>
                </label>
            </div>
        </div>
        
        <div id="migration-config" class="migration-config" style="display: none;">
            <!-- Migration-specific configuration will be loaded here -->
        </div>
        
        <div id="migration-progress" class="migration-progress" style="display: none;">
            <h3>Migration Progress</h3>
            <div class="progress-sections">
                <div class="progress-section">
                    <div class="progress-header">
                        <span class="progress-title">Customers</span>
                        <span class="progress-count" id="customers-progress">0 / 0</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="customers-progress-bar"></div>
                    </div>
                </div>
                
                <div class="progress-section">
                    <div class="progress-header">
                        <span class="progress-title">Certifications</span>
                        <span class="progress-count" id="certifications-progress">0 / 0</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="certifications-progress-bar"></div>
                    </div>
                </div>
                
                <div class="progress-section">
                    <div class="progress-header">
                        <span class="progress-title">Inventory</span>
                        <span class="progress-count" id="inventory-progress">0 / 0</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="inventory-progress-bar"></div>
                    </div>
                </div>
                
                <div class="progress-section">
                    <div class="progress-header">
                        <span class="progress-title">Bookings</span>
                        <span class="progress-count" id="bookings-progress">0 / 0</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="bookings-progress-bar"></div>
                    </div>
                </div>
            </div>
            
            <div class="migration-log">
                <h4>Migration Log</h4>
                <div id="migration-log-content" class="log-content">
                    <!-- Real-time migration logs will appear here -->
                </div>
            </div>
        </div>
    </div>`,
    
    async initialize() {
        this.setupMigrationSourceHandlers();
    },
    
    setupMigrationSourceHandlers() {
        const sourceInputs = document.querySelectorAll('input[name="migration-source"]');
        sourceInputs.forEach(input => {
            input.addEventListener('change', (e) => {
                this.loadMigrationConfig(e.target.value);
            });
        });
    },
    
    async loadMigrationConfig(source) {
        const configContainer = document.getElementById('migration-config');
        configContainer.style.display = 'block';
        
        const configs = {
            diveshop360: this.createDiveShop360Config(),
            manual: this.createManualConfig(),
            csv: this.createCSVConfig(),
            other: this.createOtherSystemConfig()
        };
        
        configContainer.innerHTML = '';
        configContainer.appendChild(configs[source]);
    },
    
    createDiveShop360Config() {
        const config = document.createElement('div');
        config.className = 'diveshop360-config';
        config.innerHTML = `
            <h3>DiveShop360.biz Integration</h3>
            <div class="integration-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="ds360-url">DiveShop360 URL:</label>
                        <input type="url" id="ds360-url" name="ds360Url" placeholder="https://yourdiveshop.diveshop360.biz" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ds360-username">Username:</label>
                        <input type="text" id="ds360-username" name="ds360Username" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ds360-password">Password:</label>
                        <input type="password" id="ds360-password" name="ds360Password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ds360-api-key">API Key (if available):</label>
                        <input type="password" id="ds360-api-key" name="ds360ApiKey">
                        <small class="field-help">Optional: Improves migration speed and reliability</small>
                    </div>
                </div>
                
                <div class="migration-options">
                    <h4>Migration Options</h4>
                    <div class="option-checkboxes">
                        <label class="option-checkbox">
                            <input type="checkbox" name="migrateCustomers" checked>
                            <span class="checkmark"></span>
                            Customer profiles and contact information
                        </label>
                        <label class="option-checkbox">
                            <input type="checkbox" name="migrateCertifications" checked>
                            <span class="checkmark"></span>
                            Certification records (all agencies)
                        </label>
                        <label class="option-checkbox">
                            <input type="checkbox" name="migrateInventory" checked>
                            <span class="checkmark"></span>
                            Equipment inventory and pricing
                        </label>
                        <label class="option-checkbox">
                            <input type="checkbox" name="migrateBookings" checked>
                            <span class="checkmark"></span>
                            Course bookings and schedules
                        </label>
                        <label class="option-checkbox">
                            <input type="checkbox" name="migrateTransactions">
                            <span class="checkmark"></span>
                            Transaction history (read-only)
                        </label>
                        <label class="option-checkbox">
                            <input type="checkbox" name="migrateInstructors" checked>
                            <span class="checkmark"></span>
                            Instructor profiles and certifications
                        </label>
                    </div>
                </div>
                
                <div class="sync-options">
                    <h4>Synchronization Settings</h4>
                    <div class="sync-settings">
                        <label class="sync-option">
                            <input type="radio" name="syncMode" value="one-time" checked>
                            <div class="option-content">
                                <strong>One-time Migration</strong>
                                <p>Complete data import with no ongoing sync</p>
                            </div>
                        </label>
                        <label class="sync-option">
                            <input type="radio" name="syncMode" value="bidirectional">
                            <div class="option-content">
                                <strong>Bidirectional Sync</strong>
                                <p>Maintain sync during transition period</p>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="test-connection-section">
                    <button type="button" id="test-ds360-connection" class="btn btn-secondary">
                        🔗 Test Connection
                    </button>
                    <div id="ds360-connection-status" class="connection-status"></div>
                </div>
            </div>
        `;
        
        // Add event listeners for DiveShop360 specific functionality
        config.querySelector('#test-ds360-connection').addEventListener('click', () => {
            this.testDiveShop360Connection();
        });
        
        return config;
    }
};
```

### **Step 6: Installation Completion**

#### **Final Installation Step and System Verification**
```javascript
const completionStep = {
    template: `
    <div class="step-content completion-step">
        <div class="completion-header">
            <div class="success-animation">
                <div class="success-icon">✓</div>
                <div class="success-ripple"></div>
            </div>
            <h2>DiveForge Installation Complete!</h2>
            <p class="completion-subtitle">Your universal dive shop management system is ready to use</p>
        </div>
        
        <div class="installation-summary">
            <h3>Installation Summary</h3>
            <div class="summary-grid">
                <div class="summary-card">
                    <div class="summary-icon">🗄️</div>
                    <div class="summary-content">
                        <h4>Database</h4>
                        <p id="summary-database">PostgreSQL configured</p>
                    </div>
                </div>
                
                <div class="summary-card">
                    <div class="summary-icon">👤</div>
                    <div class="summary-content">
                        <h4>Administrator</h4>
                        <p id="summary-admin">Account created successfully</p>
                    </div>
                </div>
                
                <div class="summary-card">
                    <div class="summary-icon">🏪</div>
                    <div class="summary-content">
                        <h4>Dive Shop</h4>
                        <p id="summary-shop">Shop configured with agencies</p>
                    </div>
                </div>
                
                <div class="summary-card">
                    <div class="summary-icon">🔄</div>
                    <div class="summary-content">
                        <h4>Migration</h4>
                        <p id="summary-migration">Data imported successfully</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="next-steps">
            <h3>Get Started with DiveForge</h3>
            <div class="steps-grid">
                <div class="step-card primary">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Access Your Dashboard</h4>
                        <p>Log in with your administrator account to start managing your dive shop</p>
                        <a href="/admin/dashboard" class="btn btn-primary">
                            🚀 Launch Dashboard
                        </a>
                    </div>
                </div>
                
                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Configure Agency Integrations</h4>
                        <p>Set up connections to your certification agencies for automated processing</p>
                        <a href="/admin/integrations/agencies" class="btn btn-secondary">
                            🌊 Setup Agencies
                        </a>
                    </div>
                </div>
                
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h4>Import Equipment Catalog</h4>
                        <p>Add your rental equipment and retail inventory to the system</p>
                        <a href="/admin/inventory/import" class="btn btn-secondary">
                            📦 Manage Inventory
                        </a>
                    </div>
                </div>
                
                <div class="step-card">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h4>Customer Portal Setup</h4>
                        <p>Configure the customer self-service portal for certification access</p>
                        <a href="/admin/portal/configure" class="btn btn-secondary">
                            👥 Setup Portal
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="system-information">
            <h3>System Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Version:</strong>
                    <span id="system-version">DiveForge v1.0.0</span>
                </div>
                <div class="info-item">
                    <strong>License:</strong>
                    <span>GPL v3</span>
                </div>
                <div class="info-item">
                    <strong>Installation Date:</strong>
                    <span id="installation-date"></span>
                </div>
                <div class="info-item">
                    <strong>Database:</strong>
                    <span id="database-info"></span>
                </div>
                <div class="info-item">
                    <strong>Supported Agencies:</strong>
                    <span id="agency-count">20+ certification bodies</span>
                </div>
                <div class="info-item">
                    <strong>Features Enabled:</strong>
                    <span id="feature-count"></span>
                </div>
            </div>
        </div>
        
        <div class="security-notice">
            <div class="notice-header">
                <span class="notice-icon">🔒</span>
                <h3>Important Security Information</h3>
            </div>
            <div class="notice-content">
                <p><strong>Installation Security:</strong> For security purposes, the DiveForge installation wizard has been automatically disabled. The installer files have been secured to prevent unauthorized access.</p>
                
                <div class="security-actions">
                    <button type="button" id="download-config" class="btn btn-secondary">
                        📄 Download Configuration Backup
                    </button>
                    <button type="button" id="view-security-report" class="btn btn-secondary">
                        🔍 View Security Report
                    </button>
                </div>
                
                <div class="security-recommendations">
                    <h4>Security Recommendations:</h4>
                    <ul>
                        <li>✓ Installation wizard disabled and secured</li>
                        <li>✓ Database credentials encrypted</li>
                        <li>✓ SSL/TLS encryption enabled</li>
                        <li>📋 Configure firewall rules for your environment</li>
                        <li>📋 Set up regular automated backups</li>
                        <li>📋 Enable monitoring and alerting</li>
                        <li>📋 Regularly update DiveForge to the latest version for security patches</li>
                        <li>📋 Review user permissions and audit logs periodically</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="community-resources">
            <h3>Community & Support</h3>
            <div class="resources-grid">
                <div class="resource-card">
                    <div class="resource-icon">📚</div>
                    <h4>Documentation</h4>
                    <p>Comprehensive guides and tutorials</p>
                    <a href="https://docs.diveforge.org" target="_blank" class="resource-link">
                        docs.diveforge.org →
                    </a>
                </div>
                
                <div class="resource-card">
                    <div class="resource-icon">💬</div>
                    <h4>Community Forum</h4>
                    <p>Get help from other dive shop owners</p>
                    <a href="https://community.diveforge.org" target="_blank" class="resource-link">
                        community.diveforge.org →
                    </a>
                </div>
                
                <div class="resource-card">
                    <div class="resource-icon">🐛</div>
                    <h4>Bug Reports</h4>
                    <p>Report issues and request features</p>
                    <a href="https://github.com/diveforge/diveforge/issues" target="_blank" class="resource-link">
                        GitHub Issues →
                    </a>
                </div>
                
                <div class="resource-card">
                    <div class="resource-icon">🔧</div>
                    <h4>Plugin Development</h4>
                    <p>Extend DiveForge with custom plugins</p>
                    <a href="https://docs.diveforge.org/plugins" target="_blank" class="resource-link">
                        Plugin Guide →
                    </a>
                </div>
            </div>
        </div>
        
        <div class="installation-complete-actions">
            <div class="primary-action">
                <a href="/admin/dashboard" class="btn btn-large btn-primary">
                    🌊 Start Managing Your Dive Shop
                </a>
            </div>
            
            <div class="secondary-actions">
                <button type="button" id="setup-wizard" class="btn btn-secondary">
                    🧙‍♂️ Launch Setup Wizard
                </button>
                <button type="button" id="demo-mode" class="btn btn-secondary">
                    🎮 Explore Demo Mode
                </button>
            </div>
        </div>
    </div>`,
    
    async initialize() {
        await this.populateInstallationSummary();
        this.setupCompletionActions();
        await this.createInstallationLock();
        this.setupSecurityReport();
    },
    
    async populateInstallationSummary() {
        const config = window.DiveForgeInstaller.config;
        
        // Update summary information
        document.getElementById('summary-database').textContent = 
            `${config.database.type.toUpperCase()} on ${config.database.host}`;
        document.getElementById('summary-admin').textContent = 
            `${config.admin.firstName} ${config.admin.lastName}`;
        document.getElementById('summary-shop').textContent = 
            `${config.shop.name} - ${config.agencies.length} agencies`;
        document.getElementById('summary-migration').textContent = 
            config.migration.source === 'manual' ? 'Fresh installation' : 
            `Migrated from ${config.migration.source}`;
        
        // Update system information
        document.getElementById('installation-date').textContent = 
            new Date().toLocaleDateString();
        document.getElementById('database-info').textContent = 
            `${config.database.type.toUpperCase()} ${config.database.version || ''}`;
        document.getElementById('feature-count').textContent = 
            `${config.shop.features?.length || 0} features enabled`;
    },
    
    async createInstallationLock() {
        try {
            const lockData = {
                timestamp: new Date().toISOString(),
                version: '1.0.0',
                installer: 'web-wizard',
                checksum: await this.generateConfigChecksum()
            };
            
            await fetch('/installer/create-lock', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(lockData)
            });
            
            console.log('Installation lock created successfully');
        } catch (error) {
            console.error('Failed to create installation lock:', error);
        }
    },
    
    setupCompletionActions() {
        // Download configuration backup
        document.getElementById('download-config').addEventListener('click', () => {
            this.downloadConfigurationBackup();
        });
        
        // View security report
        document.getElementById('view-security-report').addEventListener('click', () => {
            this.showSecurityReport();
        });
        
        // Setup wizard
        document.getElementById('setup-wizard').addEventListener('click', () => {
            window.location.href = '/admin/setup-wizard';
        });
        
        // Demo mode
        document.getElementById('demo-mode').addEventListener('click', () => {
            window.location.href = '/demo';
        });
    }
};
```

---

## Multi-Agency Certification System

### **Universal Diving Agency Integration**

DiveForge supports all major diving certification agencies through a unified API architecture that accommodates different certification standards, course structures, and digital integration capabilities.

#### **Comprehensive Agency Support Framework**

```javascript
class UniversalAgencyManager {
    constructor() {
        this.supportedAgencies = new Map();
        this.initializeSupportedAgencies();
    }
    
    initializeSupportedAgencies() {
        // Major International Agencies
        this.supportedAgencies.set('PADI', {
            name: 'Professional Association of Diving Instructors',
            founded: 1966,
            headquarters: 'Rancho Santa Margarita, CA, USA',
            scope: 'Worldwide',
            certifiedDivers: '6.6M+',
            digitalIntegration: true,
            apiEndpoint: 'https://api.padi.com',
            features: ['recreational', 'professional', 'technical'],
            certificationLevels: [
                'BUBBLEMAKER', 'SEAL_TEAM', 'JUNIOR_OPEN_WATER',
                'OPEN_WATER', 'ADVANCED_OPEN_WATER', 'RESCUE_DIVER',
                'DIVEMASTER', 'ASSISTANT_INSTRUCTOR', 'INSTRUCTOR',
                'SPECIALTY_INSTRUCTOR', 'MASTER_INSTRUCTOR', 'COURSE_DIRECTOR'
            ]
        });
        
        this.supportedAgencies.set('SSI', {
            name: 'Scuba Schools International',
            founded: 1970,
            headquarters: 'Fort Collins, CO, USA',
            scope: 'Worldwide',
            certifiedDivers: '3M+',
            digitalIntegration: true,
            digitalFirst: true,
            apiEndpoint: 'https://api.divessi.com',
            features: ['recreational', 'technical', 'freediving'],
            certificationLevels: [
                'TRY_SCUBA', 'SCUBA_SKILLS_UPDATE', 'OPEN_WATER_DIVER',
                'ADVANCED_ADVENTURER', 'STRESS_RESCUE', 'DIVE_GUIDE',
                'DIVEMASTER', 'ASSISTANT_INSTRUCTOR', 'OPEN_WATER_INSTRUCTOR',
                'SPECIALTY_INSTRUCTOR', 'INSTRUCTOR_TRAINER'
            ]
        });
        
        this.supportedAgencies.set('TDI', {
            name: 'Technical Diving International',
            founded: 1994,
            headquarters: 'Topsham, ME, USA',
            scope: 'Worldwide',
            specialization: 'Technical Diving',
            digitalIntegration: true,
            apiEndpoint: 'https://api.tdisdi.com',
            features: ['technical', 'cave', 'wreck', 'rebreather'],
            certificationLevels: [
                'NITROX_DIVER', 'ADVANCED_NITROX', 'DECOMPRESSION_PROCEDURES',
                'TRIMIX', 'HELITROX', 'CAVE_DIVER', 'WRECK_DIVER',
                'REBREATHER_DIVER', 'TECHNICAL_INSTRUCTOR', 'CAVE_INSTRUCTOR'
            ]
        });
        
        this.supportedAgencies.set('NAUI', {
            name: 'National Association of Underwater Instructors',
            founded: 1960,
            headquarters: 'Tampa, FL, USA',
            scope: 'Worldwide',
            philosophy: 'Flexible Standards',
            digitalIntegration: true,
            apiEndpoint: 'https://api.naui.org',
            features: ['recreational', 'technical', 'scientific'],
            certificationLevels: [
                'SCUBA_DIVER', 'ADVANCED_SCUBA_DIVER', 'RESCUE_SCUBA_DIVER',
                'MASTER_SCUBA_DIVER', 'ASSISTANT_INSTRUCTOR', 'INSTRUCTOR',
                'INSTRUCTOR_TRAINER', 'COURSE_DIRECTOR'
            ]
        });
        
        // Continue with all other agencies...
        this.addRemainingAgencies();
    }
    
    addRemainingAgencies() {
        // British Sub-Aqua Club
        this.supportedAgencies.set('BSAC', {
            name: 'British Sub-Aqua Club',
            founded: 1953,
            headquarters: 'Ellesmere Port, UK',
            scope: 'UK and Commonwealth',
            philosophy: 'Club-based Diving',
            certificationLevels: [
                'OCEAN_DIVER', 'SPORTS_DIVER', 'DIVE_LEADER',
                'ADVANCED_DIVER', 'FIRST_CLASS_DIVER', 'CLUB_INSTRUCTOR',
                'OPEN_WATER_INSTRUCTOR', 'ADVANCED_INSTRUCTOR'
            ]
        });
        
        // Global Underwater Explorers
        this.supportedAgencies.set('GUE', {
            name: 'Global Underwater Explorers',
            founded: 1998,
            headquarters: 'High Springs, FL, USA',
            philosophy: 'Team-based Technical Diving',
            specialization: 'Technical and Cave Diving',
            certificationLevels: [
                'RECREATIONAL_DIVER_1', 'RECREATIONAL_DIVER_2',
                'FUNDAMENTALS', 'TECH_1', 'TECH_2', 'CAVE_1', 'CAVE_2',
                'INSTRUCTOR', 'INSTRUCTOR_TRAINER'
            ]
        });
        
        // Add remaining 15+ agencies with similar structure...
    }
}
```

#### **Universal Certification Data Model**

```javascript
class UniversalCertification {
    constructor(certificationData) {
        this.id = certificationData.id || this.generateId();
        this.customerId = certificationData.customerId;
        this.agency = certificationData.agency;
        this.originalLevel = certificationData.level;
        this.normalizedLevel = this.normalizeLevel(certificationData.level, certificationData.agency);
        this.certificationNumber = certificationData.number;
        this.issueDate = new Date(certificationData.issueDate);
        this.expiryDate = certificationData.expiryDate ? new Date(certificationData.expiryDate) : null;
        this.instructor = certificationData.instructor;
        this.trainingLocation = certificationData.location;
        this.digitalCard = certificationData.digitalCard || false;
        this.equivalencies = this.calculateEquivalencies();
        this.prerequisites = this.mapPrerequisites(certificationData);
        this.metadata = this.extractMetadata(certificationData);
    }
    
    normalizeLevel(agencyLevel, agency) {
        const levelMappings = {
            // Entry Level Mappings
            'PADI_OPEN_WATER': 'ENTRY_LEVEL',
            'SSI_OPEN_WATER_DIVER': 'ENTRY_LEVEL',
            'NAUI_SCUBA_DIVER': 'ENTRY_LEVEL',
            'TDI_OPEN_WATER': 'ENTRY_LEVEL',
            'BSAC_OCEAN_DIVER': 'ENTRY_LEVEL',
            'GUE_RECREATIONAL_DIVER_1': 'ENTRY_LEVEL',
            
            // Advanced Recreational
            'PADI_ADVANCED_OPEN_WATER': 'ADVANCED_RECREATIONAL',
            'SSI_ADVANCED_ADVENTURER': 'ADVANCED_RECREATIONAL',
            'NAUI_ADVANCED_SCUBA_DIVER': 'ADVANCED_RECREATIONAL',
            'BSAC_SPORTS_DIVER': 'ADVANCED_RECREATIONAL',
            'GUE_RECREATIONAL_DIVER_2': 'ADVANCED_RECREATIONAL',
            
            // Rescue and Leadership
            'PADI_RESCUE_DIVER': 'RESCUE_LEADERSHIP',
            'SSI_STRESS_RESCUE': 'RESCUE_LEADERSHIP',
            'NAUI_RESCUE_SCUBA_DIVER': 'RESCUE_LEADERSHIP',
            'BSAC_DIVE_LEADER': 'RESCUE_LEADERSHIP',
            'GUE_FUNDAMENTALS': 'RESCUE_LEADERSHIP',
            
            // Professional Entry
            'PADI_DIVEMASTER': 'PROFESSIONAL_ENTRY',
            'SSI_DIVEMASTER': 'PROFESSIONAL_ENTRY',
            'NAUI_ASSISTANT_INSTRUCTOR': 'PROFESSIONAL_ENTRY',
            'BSAC_CLUB_INSTRUCTOR': 'PROFESSIONAL_ENTRY',
            
            // Instructor Level
            'PADI_OPEN_WATER_INSTRUCTOR': 'INSTRUCTOR',
            'SSI_OPEN_WATER_INSTRUCTOR': 'INSTRUCTOR',
            'NAUI_INSTRUCTOR': 'INSTRUCTOR',
            'TDI_INSTRUCTOR': 'INSTRUCTOR',
            'BSAC_OPEN_WATER_INSTRUCTOR': 'INSTRUCTOR',
            'GUE_INSTRUCTOR': 'INSTRUCTOR',
            
            // Technical Entry
            'TDI_ADVANCED_NITROX': 'TECHNICAL_ENTRY',
            'IANTD_ADVANCED_NITROX': 'TECHNICAL_ENTRY',
            'GUE_TECH_1': 'TECHNICAL_ENTRY',
            'UTD_TECH_1': 'TECHNICAL_ENTRY',
            'PSAI_ADVANCED_NITROX': 'TECHNICAL_ENTRY',
            
            // Technical Advanced
            'TDI_TRIMIX': 'TECHNICAL_ADVANCED',
            'IANTD_TRIMIX': 'TECHNICAL_ADVANCED',
            'GUE_TECH_2': 'TECHNICAL_ADVANCED',
            'UTD_TECH_2': 'TECHNICAL_ADVANCED',
            'NAUI_TECHNICAL_DIVER': 'TECHNICAL_ADVANCED'
        };
        
        const key = `${agency}_${agencyLevel}`;
        return levelMappings[key] || 'SPECIALTY';
    }
    
    calculateEquivalencies() {
        const equivalencyMap = new Map();
        
        // Entry level equivalencies
        if (this.normalizedLevel === 'ENTRY_LEVEL') {
            equivalencyMap.set('PADI', 'OPEN_WATER');
            equivalencyMap.set('SSI', 'OPEN_WATER_DIVER');
            equivalencyMap.set('NAUI', 'SCUBA_DIVER');
            equivalencyMap.set('TDI', 'OPEN_WATER');
            equivalencyMap.set('BSAC', 'OCEAN_DIVER');
        }
        
        // Advanced recreational equivalencies
        if (this.normalizedLevel === 'ADVANCED_RECREATIONAL') {
            equivalencyMap.set('PADI', 'ADVANCED_OPEN_WATER');
            equivalencyMap.set('SSI', 'ADVANCED_ADVENTURER');
            equivalencyMap.set('NAUI', 'ADVANCED_SCUBA_DIVER');
            equivalencyMap.set('BSAC', 'SPORTS_DIVER');
        }
        
        // Technical equivalencies
        if (this.normalizedLevel === 'TECHNICAL_ENTRY') {
            equivalencyMap.set('TDI', 'ADVANCED_NITROX');
            equivalencyMap.set('IANTD', 'ADVANCED_NITROX');
            equivalencyMap.set('GUE', 'TECH_1');
            equivalencyMap.set('UTD', 'TECH_1');
        }
        
        // Remove the current agency from equivalencies
        equivalencyMap.delete(this.agency);
        
        return Array.from(equivalencyMap.entries()).map(([agency, level]) => ({
            agency,
            level,
            normalized: this.normalizedLevel
        }));
    }
    
    async mapPrerequisites(certificationData) {
        const agency = certificationData.agency;
        const level = certificationData.level;
        const mapped = [];
        
        // Example mapping logic
        if (agency === 'PADI') {
            if (level === 'OPEN_WATER') {
                mapped.push('BUBBLEMAKER', 'SEAL_TEAM');
            } else if (level === 'ADVANCED_OPEN_WATER') {
                mapped.push('OPEN_WATER');
            }
        } else if (agency === 'SSI') {
            if (level === 'OPEN_WATER_DIVER') {
                mapped.push('TRY_SCUBA');
            } else if (level === 'ADVANCED_ADVENTURER') {
                mapped.push('OPEN_WATER_DIVER');
            }
        }
        
        return mapped;
    }
    
    extractMetadata(certificationData) {
        return {
            sourceSystem: certificationData.sourceSystem || 'unknown',
            migrationDate: certificationData.migrationDate || new Date().toISOString(),
            originalId: certificationData.originalId || null,
            integrityCheck: this.performDataIntegrityCheck(certificationData)
        };
    }
    
    performDataIntegrityCheck(data) {
        // Simple integrity check example
        return data.id && data.customerId && data.agency && data.level;
    }
}
```

#### **Agency-Specific Integration Adapters**

```javascript
class AgencyIntegrationManager {
    constructor() {
        this.agencies = new Map();
        this.initializeAgencyAdapters();
    }
    
    initializeAgencyAdapters() {
        this.agencies.set('PADI', new PADIIntegration());
        this.agencies.set('SSI', new SSIIntegration());
        this.agencies.set('TDI', new TDIIntegration());
        this.agencies.set('SDI', new SDIIntegration());
        this.agencies.set('NAUI', new NAUIIntegration());
        this.agencies.set('BSAC', new BSACIntegration());
        this.agencies.set('GUE', new GUEIntegration());
        this.agencies.set('UTD', new UTDIntegration());
        this.agencies.set('IANTD', new IANTDIntegration());
        this.agencies.set('RAID', new RAIDIntegration());
        this.agencies.set('PSAI', new PSAIIntegration());
        this.agencies.set('ACUC', new ACUCIntegration());
    }
    
    async processCertification(agency, certificationData) {
        const adapter = this.agencies.get(agency);
        if (!adapter) {
            throw new Error(`Unsupported agency: ${agency}`);
        }
        
        return await adapter.processCertification(certificationData);
    }
    
    async syncWithAgency(agency, shopCredentials) {
        const adapter = this.agencies.get(agency);
        if (!adapter) {
            throw new Error(`Unsupported agency: ${agency}`);
        }
        
        return await adapter.syncCertifications(shopCredentials);
    }
    
    async validateInstructorCredentials(agency, instructorData) {
        const adapter = this.agencies.get(agency);
        return await adapter.validateInstructor(instructorData);
    }
}

// PADI Integration Implementation
class PADIIntegration {
    constructor() {
        this.apiEndpoint = 'https://api.padi.com';
        this.version = 'v2';
        this.supportedCourses = [
            'BUBBLEMAKER', 'SEAL_TEAM', 'JUNIOR_OPEN_WATER',
            'OPEN_WATER', 'ADVANCED_OPEN_WATER', 'RESCUE_DIVER',
            'DIVEMASTER', 'ASSISTANT_INSTRUCTOR', 'INSTRUCTOR',
            'DEEP_SPECIALTY', 'WRECK_SPECIALTY', 'NIGHT_SPECIALTY',
            'NITROX_SPECIALTY', 'PEAK_PERFORMANCE_BUOYANCY'
        ];
        this.ratios = {
            'OPEN_WATER': { pool: 8, openWater: 8 },
            'ADVANCED_OPEN_WATER': { student: 8 },
            'RESCUE_DIVER': { student: 8 }
        };
    }
    
    async processCertification(data) {
        const certification = new UniversalCertification({
            agency: 'PADI',
            ...data,
            digitalCard: true,
            features: {
                eCard: true,
                replacementCard: true,
                verification: true
            }
        });
        
        // PADI-specific validation
        await this.validatePADIStandards(certification);
        
        return certification;
    }
    
    async validatePADIStandards(certification) {
        const standards = {
            minAge: this.getMinimumAge(certification.originalLevel),
            prerequisites: this.getPrerequisites(certification.originalLevel),
            ratios: this.ratios[certification.originalLevel]
        };
        
        // Implement PADI-specific validation logic
        return standards;
    }
    
    async syncCertifications(credentials) {
        try {
            const response = await fetch(`${this.apiEndpoint}/${this.version}/certifications`, {
                headers: {
                    'Authorization': `Bearer ${credentials.apiKey}`,
                    'X-PADI-Center': credentials.centerNumber,
                    'Content-Type': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error(`PADI API error: ${response.status}`);
            }
            
            const certifications = await response.json();
            return certifications.map(cert => this.processCertification(cert));
        } catch (error) {
            console.error('PADI sync failed:', error);
            throw error;
        }
    }
}

// SSI Integration Implementation
class SSIIntegration {
    constructor() {
        this.apiEndpoint = 'https://api.divessi.com';
        this.digitalFirst = true;
        this.supportedCourses = [
            'TRY_SCUBA', 'SCUBA_SKILLS_UPDATE', 'OPEN_WATER_DIVER',
            'ADVANCED_ADVENTURER', 'STRESS_RESCUE', 'DIVE_GUIDE',
            'DIVEMASTER', 'ASSISTANT_INSTRUCTOR', 'OPEN_WATER_INSTRUCTOR'
        ];
        this.digitalFeatures = {
            onlineLearning: true,
            digitalCertification: true,
            appIntegration: true,
            progressTracking: true
        };
    }
    
    async processCertification(data) {
        return new UniversalCertification({
            agency: 'SSI',
            digitalCard: true,
            digitalFirst: true,
            onlineLearning: this.digitalFeatures.onlineLearning,
            ...data
        });
    }
    
    async syncDigitalCertifications(credentials) {
        const response = await fetch(`${this.apiEndpoint}/certifications/digital`, {
            headers: {
                'Authorization': `Bearer ${credentials.token}`,
                'X-SSI-Center-ID': credentials.centerId,
                'Accept': 'application/json'
            }
        });
        
        const digitalCerts = await response.json();
        return digitalCerts.map(cert => ({
            ...cert,
            digitalCard: true,
            qrCode: cert.verification_qr,
            appAccess: true
        }));
    }
}

// TDI Technical Diving Integration
class TDIIntegration {
    constructor() {
        this.apiEndpoint = 'https://api.tdisdi.com';
        this.technicalFocus = true;
        this.supportedCourses = [
            'NITROX_DIVER', 'ADVANCED_NITROX', 'DECOMPRESSION_PROCEDURES',
            'TRIMIX', 'HELITROX', 'CAVE_DIVER', 'WRECK_DIVER',
            'REBREATHER_DIVER', 'TECHNICAL_INSTRUCTOR', 'CAVE_INSTRUCTOR'
        ];
        this.gasBlendingCourses = [
            'ADVANCED_NITROX', 'TRIMIX', 'HELITROX'
        ];
        this.technicalRatios = {
            'ADVANCED_NITROX': { student: 8, depth: '40m' },
            'TRIMIX': { student: 4, depth: '60m' },
            'CAVE_DIVER': { student: 3, environment: 'overhead' }
        };
    }
    
    async processCertification(data) {
        const certification = new UniversalCertification({
            agency: 'TDI',
            technical: true,
            gasBlending: this.requiresGasBlending(data.course),
            maxDepth: this.getMaxDepth(data.course),
            ...data
        });
        
        // Add technical-specific metadata
        certification.metadata.technical = {
            gasBlending: this.gasBlendingCourses.includes(data.course),
            decompression: ['DECOMPRESSION_PROCEDURES', 'TRIMIX'].includes(data.course),
            overhead: ['CAVE_DIVER', 'WRECK_DIVER'].includes(data.course),
            ratios: this.technicalRatios[data.course]
        };
        
        return certification;
    }
    
    requiresGasBlending(course) {
        return this.gasBlendingCourses.includes(course);
    }
    
    getMaxDepth(course) {
        const depths = {
            'ADVANCED_NITROX': '40m',
            'TRIMIX': '60m',
            'HELITROX': '45m',
            'CAVE_DIVER': 'overhead',
            'WRECK_DIVER': '50m'
        };
        return depths[course] || 'recreational';
    }
}
```

---

## DiveShop360.biz API Compatibility Layer

### **Migration and Compatibility Framework**

#### **Comprehensive API Compatibility Mapping**

```javascript
class DiveShop360CompatibilityLayer {
    constructor() {
        this.apiMappings = new Map();
        this.dataTransformers = new Map();
        this.migrationState = new Map();
        this.setupAPIMapping();
        this.setupDataTransformers();
    }
    
    setupAPIMapping() {
        // Legacy DiveShop360 endpoints mapped to DiveForge API
        this.apiMappings.set('/ds360/api/customers', {
            diveForgeEndpoint: '/api/v1/customers',
            method: 'GET',
            transformation: 'customer',
            pagination: true,
            filters: ['active', 'certification_level', 'last_activity']
        });
        
        this.apiMappings.set('/ds360/api/inventory', {
            diveForgeEndpoint: '/api/v1/inventory',
            method: 'GET',
            transformation: 'inventory',
            pagination: true,
            filters: ['category', 'availability', 'agency_compatibility']
        });
        
        this.apiMappings.set('/ds360/api/bookings', {
            diveForgeEndpoint: '/api/v1/bookings',
            method: 'GET',
            transformation: 'booking',
            pagination: true,
            filters: ['date_range', 'status', 'agency']
        });
        
        this.apiMappings.set('/ds360/api/certifications', {
            diveForgeEndpoint: '/api/v1/certifications',
            method: 'GET',
            transformation: 'certification',
            multiAgency: true,
            validation: 'universal_standards'
        });
        
        this.apiMappings.set('/ds360/api/reports', {
            diveForgeEndpoint: '/api/v1/reports',
            method: 'GET',
            transformation: 'report',
            realTime: true,
            multiAgency: true
        });
    }
    
    setupDataTransformers() {
        // Customer data transformation with multi-agency support
        this.dataTransformers.set('customer', (ds360Data) => ({
            id: ds360Data.customer_id,
            firstName: ds360Data.first_name,
            lastName: ds360Data.last_name,
            email: ds360Data.email_address,
            phone: ds360Data.phone_number,
            dateOfBirth: ds360Data.date_of_birth,
            
            // Enhanced emergency contact
            emergencyContact: {
                name: ds360Data.emergency_contact_name,
                phone: ds360Data.emergency_contact_phone,
                relationship: ds360Data.emergency_contact_relationship,
                email: ds360Data.emergency_contact_email
            },
            
            // Multi-agency certifications
            certifications: this.transformCertifications(ds360Data.certifications),
            
            // Enhanced medical information
            medicalInfo: {
                conditions: ds360Data.medical_conditions,
                restrictions: ds360Data.medical_restrictions,
                medications: ds360Data.current_medications,
                allergies: ds360Data.allergies,
                lastPhysical: ds360Data.last_physical_date,
                physicianApproval: ds360Data.physician_approval === 'Y'
            },
            
            // Equipment preferences
            equipmentSizes: {
                wetsuit: ds360Data.wetsuit_size,
                bcd: ds360Data.bcd_size,
                fins: ds360Data.fin_size,
                mask: ds360Data.mask_type_preference,
                weight: ds360Data.weight_requirement
            },
            
            // Customer preferences
            preferences: {
                agencies: this.parseAgencyPreferences(ds360Data.preferred_agencies),
                communications: ds360Data.communication_preferences,
                language: ds360Data.preferred_language || 'en',
                newsletter: ds360Data.newsletter_subscription === 'Y'
            },
            
            // Audit trail
            createdAt: ds360Data.created_date,
            updatedAt: ds360Data.last_modified,
            source: 'diveshop360_migration'
        }));
        
        // Enhanced inventory transformation with agency compatibility
        this.dataTransformers.set('inventory', (ds360Data) => ({
            id: ds360Data.item_id,
            sku: ds360Data.sku_code,
            name: ds360Data.item_name,
            description: ds360Data.item_description,
            category: ds360Data.category_name,
            subcategory: ds360Data.subcategory_name,
            manufacturer: ds360Data.manufacturer,
            model: ds360Data.model_number,
            
            // Multi-agency compatibility determination
            agencyCompatibility: this.determineAgencyCompatibility(ds360Data),
            
            // Enhanced pricing structure
            pricing: {
                retail: parseFloat(ds360Data.retail_price),
                cost: parseFloat(ds360Data.cost_price),
                wholesale: parseFloat(ds360Data.wholesale_price),
                currency: ds360Data.currency || 'USD',
                taxable: ds360Data.taxable === 'Y',
                discountEligible: ds360Data.discount_eligible === 'Y'
            },
            
            // Comprehensive inventory tracking
            inventory: {
                quantity: parseInt(ds360Data.quantity_on_hand),
                reserved: parseInt(ds360Data.quantity_reserved),
                available: parseInt(ds360Data.quantity_available),
                committed: parseInt(ds360Data.quantity_committed),
                reorderPoint: parseInt(ds360Data.reorder_point),
                reorderQuantity: parseInt(ds360Data.reorder_quantity),
                location: ds360Data.storage_location,
                binLocation: ds360Data.bin_location
            },
            
            // Enhanced rental management
            rental: {
                isRentable: ds360Data.is_rental_item === 'Y',
                dailyRate: parseFloat(ds360Data.rental_rate_daily),
                weeklyRate: parseFloat(ds360Data.rental_rate_weekly),
                monthlyRate: parseFloat(ds360Data.rental_rate_monthly),
                depositRequired: parseFloat(ds360Data.rental_deposit),
                condition: ds360Data.rental_condition,
                lastService: ds360Data.last_service_date,
                nextService: ds360Data.next_service_date,
                serviceInterval: ds360Data.service_interval_days
            },
            
            // Equipment specifications
            specifications: {
                size: ds360Data.size,
                color: ds360Data.color,
                weight: ds360Data.weight,
                dimensions: ds360Data.dimensions,
                material: ds360Data.material,
                certifications: ds360Data.equipment_certifications
            },
            
            // Multi-agency course material compatibility
            courseCompatibility: this.determineCourseCompatibility(ds360Data),
            
            // Enhanced metadata
            metadata: {
                serialNumbers: ds360Data.serial_numbers?.split(',') || [],
                images: ds360Data.image_urls?.split(',') || [],
                documents: ds360Data.document_urls?.split(',') || [],
                tags: ds360Data.tags?.split(',') || [],
                notes: ds360Data.internal_notes
            }
        }));
        
        // Multi-agency certification transformation
        this.dataTransformers.set('certification', (ds360Data) => {
            const agency = this.normalizeAgencyName(ds360Data.certifying_agency);
            
            return {
                id: ds360Data.certification_id,
                customerId: ds360Data.customer_id,
                agency: agency,
                level: ds360Data.certification_level,
                normalizedLevel: this.normalizeCertificationLevel(ds360Data.certification_level, agency),
                certificationNumber: ds360Data.cert_number,
                issueDate: ds360Data.issue_date,
                expiryDate: ds360Data.expiry_date,
                
                // Instructor information
                instructor: {
                    name: ds360Data.instructor_name,
                    number: ds360Data.instructor_number,
                    agency: agency,
                    signature: ds360Data.instructor_signature
                },
                
                // Training details
                training: {
                    location: ds360Data.training_location,
                    startDate: ds360Data.training_start_date,
                    completionDate: ds360Data.training_completion_date,
                    hours: ds360Data.training_hours,
                    dives: ds360Data.training_dives
                },
                
                // Cross-agency equivalencies
                equivalencies: this.calculateCrossAgencyEquivalencies(ds360Data.certification_level, agency),
                
                // Digital certification support
                digital: {
                    cardUrl: ds360Data.digital_card_url,
                    qrCode: ds360Data.verification_qr_code,
                    blockchain: ds360Data.blockchain_hash,
                    verified: ds360Data.verification_status === 'verified'
                },
                
                // Migration metadata
                migrationData: {
                    originalSystem: 'diveshop360',
                    migrationDate: new Date().toISOString(),
                    originalId: ds360Data.certification_id,
                    dataIntegrity: 'verified'
                }
            };
        });
    }
    
    determineAgencyCompatibility(itemData) {
        const compatibilityRules = {
            'PADI': {
                keywords: ['padi', 'recreational', 'open water', 'advanced', 'rescue', 'divemaster'],
                categories: ['training_materials', 'recreational_equipment', 'certification_items']
            },
            'SSI': {
                keywords: ['ssi', 'digital', 'online', 'scuba schools'],
                categories: ['digital_materials', 'training_equipment', 'apps']
            },
            'TDI': {
                keywords: ['technical', 'trimix', 'nitrox', 'cave', 'wreck', 'decompression'],
                categories: ['technical_equipment', 'gas_analysis', 'technical_training']
            },
            'NAUI': {
                keywords: ['naui', 'flexible', 'rescue', 'leadership'],
                categories: ['leadership_materials', 'rescue_equipment', 'flexible_training']
            },
            'BSAC': {
                keywords: ['bsac', 'british', 'club', 'sports diver'],
                categories: ['club_equipment', 'uk_training', 'sports_diving']
            },
            'GUE': {
                keywords: ['gue', 'team', 'standardized', 'fundamentals', 'exploration'],
                categories: ['team_equipment', 'standardized_gear', 'exploration_tools']
            },
            'UNIVERSAL': {
                keywords: ['universal', 'standard', 'basic', 'general'],
                categories: ['basic_equipment', 'universal_tools', 'general_supplies']
            }
        };
        
        const itemName = itemData.item_name?.toLowerCase() || '';
        const itemDesc = itemData.item_description?.toLowerCase() || '';
        const category = itemData.category_name?.toLowerCase() || '';
        const compatible = [];
        
        Object.entries(compatibilityRules).forEach(([agency, rules]) => {
            const keywordMatch = rules.keywords.some(keyword => 
                itemName.includes(keyword) || itemDesc.includes(keyword)
            );
            const categoryMatch = rules.categories.some(cat => 
                category.includes(cat.replace('_', ' '))
            );
            
            if (keywordMatch || categoryMatch) {
                compatible.push(agency);
            }
        });
        
        // If no specific agency match, mark as universal
        return compatible.length > 0 ? compatible : ['UNIVERSAL'];
    }
    
    async migrateData(dataType, ds360Data, options = {}) {
        const transformer = this.dataTransformers.get(dataType);
        if (!transformer) {
            throw new Error(`No transformer found for data type: ${dataType}`);
        }
        
        try {
            // Transform data using appropriate transformer
            const transformedData = transformer(ds360Data);
            
            // Validate transformed data
            await this.validateTransformedData(dataType, transformedData);
            
            // Apply agency-specific processing if needed
            if (options.agencyProcessing && dataType === 'certification') {
                transformedData.agencyValidation = await this.validateAgencyStandards(transformedData);
            }
            
            // Track migration progress
            this.updateMigrationProgress(dataType, 1);
            
            return transformedData;
        } catch (error) {
            console.error(`Migration error for ${dataType}:`, error);
            throw new Error(`Failed to migrate ${dataType}: ${error.message}`);
        }
    }
    
    async performBulkMigration(migrationConfig) {
        const results = {
            customers: { success: 0, failed: 0, errors: [] },
            certifications: { success: 0, failed: 0, errors: [] },
            inventory: { success: 0, failed: 0, errors: [] },
            bookings: { success: 0, failed: 0, errors: [] }
        };
        
        try {
            // Migrate in order: customers, certifications, inventory, bookings
            if (migrationConfig.migrateCustomers) {
                await this.migrateCustomers(migrationConfig, results);
            }
            
            if (migrationConfig.migrateCertifications) {
                await this.migrateCertifications(migrationConfig, results);
            }
            
            if (migrationConfig.migrateInventory) {
                await this.migrateInventory(migrationConfig, results);
            }
            
            if (migrationConfig.migrateBookings) {
                await this.migrateBookings(migrationConfig, results);
            }
            
            // Generate migration report
            const report = this.generateMigrationReport(results);
            
            return {
                success: true,
                results,
                report,
                timestamp: new Date().toISOString()
            };
            
        } catch (error) {
            console.error('Bulk migration failed:', error);
            return {
                success: false,
                error: error.message,
                results,
                timestamp: new Date().toISOString()
            };
        }
    }
}
```

---

## Enterprise Application Architecture

### **Microservices vs Modular Monolith Decision Framework**

#### **Architecture Decision Matrix**

```typescript
interface ArchitectureDecision {
    teamSize: number;
    expectedLoad: 'low' | 'medium' | 'high' | 'variable';
    deploymentComplexity: 'simple' | 'moderate' | 'complex';
    dataConsistency: 'eventual' | 'strong';
    operationalExpertise: 'basic' | 'intermediate' | 'advanced';
    recommendation: 'modular-monolith' | 'microservices' | 'hybrid';
}

class ArchitectureDecisionEngine {
    evaluateArchitecture(requirements: ArchitectureDecision): string {
        const score = this.calculateArchitectureScore(requirements);
        
        if (score < 3) {
            return 'modular-monolith';
        } else if (score > 7) {
            return 'microservices';
        } else {
            return 'hybrid';
        }
    }
    
    private calculateArchitectureScore(req: ArchitectureDecision): number {
        let score = 0;
        
        // Team size factor
        if (req.teamSize > 15) score += 3;
        else if (req.teamSize > 8) score += 2;
        else score += 0;
        
        // Load complexity
        if (req.expectedLoad === 'high' || req.expectedLoad === 'variable') score += 2;
        
        // Deployment complexity tolerance
        if (req.deploymentComplexity === 'complex') score += 2;
        
        // Data consistency requirements
        if (req.dataConsistency === 'eventual') score += 1;
        
        // Operational expertise
        if (req.operationalExpertise === 'advanced') score += 2;
        else if (req.operationalExpertise === 'intermediate') score += 1;
        
        return score;
    }
}
```

#### **Modular Monolith Benefits for Most Dive Shops**

```typescript
// Modular Monolith Architecture for DiveForge
class DiveForgeModularMonolith {
    private modules: Map<string, Module> = new Map();
    
    constructor() {
        this.initializeModules();
    }
    
    private initializeModules() {
        // Core Business Modules
        this.modules.set('customer-management', new CustomerManagementModule());
        this.modules.set('certification-processing', new CertificationProcessingModule());
        this.modules.set('inventory-management', new InventoryManagementModule());
        this.modules.set('booking-system', new BookingSystemModule());
        this.modules.set('equipment-rental', new EquipmentRentalModule());
        
        // Multi-Agency Modules
        this.modules.set('agency-integration', new AgencyIntegrationModule());
        this.modules.set('standards-validation', new StandardsValidationModule());
        this.modules.set('certification-equivalency', new CertificationEquivalencyModule());
        
        // Enterprise Modules
        this.modules.set('security-framework', new SecurityFrameworkModule());
        this.modules.set('audit-logging', new AuditLoggingModule());
        this.modules.set('reporting-analytics', new ReportingAnalyticsModule());
        this.modules.set('plugin-management', new PluginManagementModule());
    }
    
    // Benefits of Modular Monolith:
    // 1. Simplified deployment and operational complexity
    // 2. Easier data consistency across business operations
    // 3. Lower operational overhead suitable for smaller teams
    // 4. Better performance with reduced network latency
    // 5. Easier debugging and troubleshooting
    // 6. Simpler transaction management across modules
}
```

### **Domain-Driven Design Implementation**

#### **Core Domain Models**

```typescript
// Dive Shop Aggregate Root
class DiveShop extends AggregateRoot {
    private constructor(
        public readonly id: DiveShopId,
        public readonly name: string,
        public readonly agencies: AgencyPartnership[],
        private equipment: Equipment[],
        private instructors: Instructor[]
    ) {
        super();
    }
    
    static create(name: string, agencies: AgencyPartnership[]): DiveShop {
        const id = DiveShopId.generate();
        const shop = new DiveShop(id, name, agencies, [], []);
        
        // Fire domain event
        shop.addDomainEvent(new DiveShopCreatedEvent(shop));
        
        return shop;
    }
    
    addEquipment(equipment: Equipment) {
        this.equipment.push(equipment);
        this.addDomainEvent(new EquipmentAddedEvent(this.id, equipment));
    }
    
    addInstructor(instructor: Instructor) {
        this.instructors.push(instructor);
        this.addDomainEvent(new InstructorAddedEvent(this.id, instructor));
    }
    
    // Business logic methods
    assignInstructorToCourse(instructorId: string, courseId: string) {
        const instructor = this.instructors.find(i => i.id === instructorId);
        if (!instructor) {
            throw new Error('Instructor not found');
        }
        
        instructor.assignToCourse(courseId);
    }
    
    // Query methods
    getAvailableEquipment() {
        return this.equipment.filter(e => e.isAvailable());
    }
    
    getInstructorSchedule(instructorId: string) {
        const instructor = this.instructors.find(i => i.id === instructorId);
        return instructor ? instructor.getSchedule() : null;
    }
}
```
