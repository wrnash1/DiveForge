# DiveForge 🌊

**The First Universal Open Source Dive Shop Management Platform**

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![Version](https://img.shields.io/badge/Version-1.0.0-green.svg)]()
[![Agencies](https://img.shields.io/badge/Agencies-20+-orange.svg)]()
[![Community](https://img.shields.io/badge/Community-Driven-purple.svg)]()

DiveForge is the first truly universal, open source, enterprise-grade dive shop management platform supporting **ALL major diving certification agencies**. Built for the community under GPL v3, DiveForge unifies operations, compliance, and customer experience for dive shops worldwide—breaking down artificial barriers between agencies and empowering shops with unprecedented flexibility, extensibility, and security.

---

## 🌊 Why DiveForge?

-   **Universal Agency Support:** PADI, SSI, TDI/SDI, NAUI, BSAC, GUE, IANTD, RAID, and 20+ regional agencies in one platform.
-   **Enterprise-Grade:** PCI DSS & GDPR compliance, multi-tenant, audit logging, advanced security.
-   **Open Source Freedom:** GPL v3 license ensures community ownership and extensibility.
-   **Migration Ready:** Seamless transition from DiveShop360.biz and other legacy systems.
-   **Modular & Extensible:** Plugin/theme architecture, API-first design, and community-driven enhancements.

---

## 🏗️ Key Features

### Multi-Agency Certification Management

-   Universal certification & cross-agency equivalency
-   Agency-specific compliance & standards validation
-   Unified student records and multi-agency course management

### Enterprise Business Operations

-   Equipment & inventory management (rental, sales, maintenance, warranty)
-   Booking system for trips, courses, and equipment
-   Customer portal with self-service, loyalty, and digital wallet
-   Financial management: multi-currency, payment plans, advanced reporting
-   Safety & compliance: incident tracking, audit trails, regulatory validation

### Advanced Modules

-   **Equipment Service/Repair:** Work orders, parts/labor tracking, warranty, notifications
-   **Air & Gift Card Management:** Air fill tracking, digital/physical gift cards, multi-location support
-   **Trip & Charter Management:** Multi-day trips, boat scheduling, crew, weather, manifests
-   **Course Management:** Flexible scheduling, skills tracking, instructor rotation, digital/physical materials
-   **Commission/Incentive:** Complex commission structures, bonuses, incentive programs
-   **Vendor & Inventory:** Automated vendor catalogs, drop shipping, product variants, consignment, used gear
-   **Customer Loyalty:** Points, tiers, gamification, automated campaigns, personalized offers
-   **Reporting & Analytics:** Predictive analytics, custom report builder, dashboards, compliance reporting
-   **Dive Site Management:** Local/global site database, real-time conditions, interactive maps, community features
-   **Commercial/B2B:** Corporate accounts, PO/invoicing, credit, contract management, B2B portals

### Critical Operations

-   **Boat Operations:** Fleet, maintenance, safety, compliance, crew, manifests
-   **Compressor/Nitrox:** Hour tracking, oil/filter management, air quality, analyzer calibration, gas blending
-   **Security Cameras:** Multi-location surveillance, incident review, analytics, privacy compliance
-   **Student Validation:** Prerequisite checks, standards engine, skills tracking, audit trail
-   **Digital Forms:** E-signatures, multi-language, compliance, version control
-   **Photography/Social:** Certification photos, consent, social media automation, content management
-   **Advanced Student Management:** Lifecycle tracking, quality assurance, alumni, continuing education

---

## 📊 Architecture Overview

### Core Business Modules

-   **Universal Agency Integration:** Certification processing, standards compliance, cross-agency equivalency, digital cards
-   **Customer Management:** Profiles, medical/certification history, loyalty, portal, communication preferences
-   **Course & Certification:** Multi-agency catalog, scheduling, prerequisites, online learning, skill tracking
-   **Equipment & Inventory:** Multi-location, predictive analytics, automated image management, rental/service, compressor/nitrox, IoT integration
-   **Booking & Scheduling:** Course/trip scheduling, resource allocation, weather integration, calendar sync
-   **Financial Management:** Chart of accounts, revenue/expense tracking, payroll, compliance, multi-currency
-   **HR Management:** Staff profiles, certification tracking, performance, training, compliance
-   **CRM:** Segmentation, marketing automation, customer journey, lifecycle management
-   **Supply Chain:** Vendor management, procurement, inventory optimization, demand forecasting
-   **Technology Infrastructure:** API-first, plugin/theme system, mobile apps, POS, e-commerce, integration platform
-   **Security & Compliance:** Zero-trust, RBAC, encryption, audit logging, privacy, disaster recovery

### Technology Stack

-   **Backend:** Node.js/Express.js or PHP/Laravel
-   **Frontend:** React (PWA), mobile-first, theme/plugin system
-   **Database:** PostgreSQL (primary), MySQL, SQLite, SQL Server
-   **Authentication:** OAuth 2.0/OpenID Connect, MFA
-   **Containerization:** Docker, Kubernetes
-   **Security:** TLS 1.3, AES-256-GCM, PCI DSS, GDPR

---

## 🚀 Quick Start

### Prerequisites

-   Node.js 18+ or PHP 8.1+
-   Database (PostgreSQL recommended, MySQL, SQLite supported)
-   Web server (Apache, Nginx, or built-in dev server)

### Installation

1. **Clone the Repository**

    ```bash
    git clone https://github.com/diveforge/diveforge.git
    cd diveforge
    ```

2. **Start Installation Wizard**

    ```bash
    # Node.js
    npm install
    npm run install:wizard

    # PHP
    composer install
    php artisan diveforge:install

    # Docker
    docker-compose up -d
    ```

3. **Access Installation Wizard**
   Open your browser to `http://localhost:3000/install`

---

## 🛠️ Development Setup

```bash
# Install dependencies
npm install

# Set up environment
cp .env.example .env
# Configure your database and API keys

# Run database migrations
npm run migrate

# Start development servers
npm run dev        # Backend API
npm run dev:web    # Web interface
npm run dev:portal # Customer portal
```

### Running Tests

```bash
npm test                    # Unit tests
npm run test:integration   # Integration tests
npm run test:agencies      # Agency-specific tests
npm run test:e2e          # End-to-end tests
```

---

## 🤝 Contributing

DiveForge thrives on community contributions! We welcome developers, dive professionals, and enthusiasts.

-   **Fork** the repository
-   **Create** a feature branch (`git checkout -b feature/amazing-feature`)
-   **Commit** your changes (`git commit -m 'Add amazing feature'`)
-   **Push** to the branch (`git push origin feature/amazing-feature`)
-   **Open** a Pull Request

See [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

### **Contribution Areas**

-   **Agency Integrations**: Add support for additional certification agencies
-   **Equipment Modules**: Expand equipment tracking capabilities
-   **Language Support**: Internationalization and localization
-   **Mobile Features**: Enhanced mobile functionality
-   **Plugin Development**: Community plugins and extensions

### **Development Guidelines**

-   Follow GPL v3 licensing requirements
-   Include unit tests for new features
-   Update documentation for API changes
-   Respect agency-specific standards and requirements

---

## 📖 Documentation

-   [User Guide](docs/users/README.md)
-   [Developer Guide](docs/api/README.md)
-   [Plugin Development](docs/plugins/README.md)
-   [Database Schema](docs/database/README.md)
-   [Security](docs/security/README.md)
-   [Admin Guide](docs/admin/README.md)

---

## 📊 Roadmap

-   **Current:** Universal multi-agency support, web-based install, migration tools, customer portal, equipment management
-   **Next:** Mobile app, advanced analytics, equipment maintenance, multi-language, dive computer integration, AI insights, blockchain verification

See [Developer_Guide.md](Developer_Guide.md) for full roadmap and architecture.

---

## 📄 License

DiveForge is licensed under the [GNU General Public License v3.0](LICENSE).

```
DiveForge - Enterprise Dive Shop Management Application
Copyright (C) 2025 DiveForge Community

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

---

## 🙏 **Acknowledgments**

### **Core Contributors**

-   **DiveForge Community**: Open source developers and diving professionals worldwide
-   **Certification Agencies**: PADI, SSI, TDI/SDI, NAUI, BSAC, GUE, IANTD, RAID for collaboration
-   **Dive Shop Partners**: Beta testing and feedback from dive operations globally
-   **Open Source Community**: Libraries and frameworks that make DiveForge possible

### **Special Thanks**

-   **Free Software Foundation**: For the GPL v3 license framework
-   **Diving Industry Professionals**: Subject matter expertise and standards guidance
-   **Beta Testing Dive Shops**: Real-world validation and improvement suggestions

---

## 📞 **Support**

### **Community Support**

-   **GitHub Issues**: [Report bugs and request features](https://github.com/diveforge/diveforge/issues)
-   **Community Forum**: [Get help from other users](https://github.com/diveforge/diveforge/discussions)
-   **Documentation**: [Comprehensive guides and tutorials](docs/)

### **Commercial Support**

Professional support available through certified DiveForge partners for:

-   Custom agency integrations
-   Enterprise deployment assistance
-   Training and consultation services
-   Priority feature development

---

## 🔗 **Links**

-   **Website**: [diveforge.org](https://diveforge.org)
-   **Documentation**: [docs.diveforge.org](https://docs.diveforge.org)
-   **Community**: [community.diveforge.org](https://community.diveforge.org)
-   **API Reference**: [api.diveforge.org](https://api.diveforge.org)

---

<div align="center">

**🌊 Dive into the Future of Dive Shop Management 🌊**

_Made with ❤️ by the diving community, for the diving community_

[⭐ Star this repository](https://github.com/diveforge/diveforge) | [🤝 Contribute](CONTRIBUTING.md) | [📖 Documentation](docs/) | [💬 Community](https://discord.gg/diveforge)

</div>
### **Current Version: 1.0.0**

-   ✅ Universal multi-agency support
-   ✅ Web-based installation wizard
-   ✅ DiveShop360.biz migration tools
-   ✅ Customer portal with certification display
-   ✅ Equipment management and tracking

### **Version 1.1.0 (Q2 2025)**

-   🔄 Enhanced mobile application
-   🔄 Advanced reporting and analytics
-   🔄 Equipment maintenance scheduling
-   🔄 Multi-language support (Spanish, French, German)

### **Version 1.2.0 (Q3 2025)**

-   📋 Integration with dive computer manufacturers
-   📋 Advanced certification pathway recommendations
-   📋 Equipment marketplace integration
-   📋 Instructor scheduling optimization

### **Version 2.0.0 (Q4 2025)**

-   📋 AI-powered customer insights
-   📋 Advanced safety prediction algorithms
-   📋 Blockchain certification verification
-   📋 Global dive site integration

---

## 🏆 **Success Stories**

_"DiveForge transformed our multi-agency dive shop operations. We now seamlessly handle PADI, SSI, and TDI certifications in one system, saving us hours of administrative work daily."_  
**— Marina Santos, Blue Ocean Diving Center**

_"The open source nature allowed us to customize DiveForge for our technical diving focus while maintaining compatibility with all major agencies."_  
**— Dr. James Mitchell, Technical Diving Institute**

---

## 📄 **License**

DiveForge is licensed under the [GNU General Public License v3.0](LICENSE).

```
DiveForge - Enterprise Dive Shop Management Application
Copyright (C) 2025 DiveForge Community

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

---

## 🙏 **Acknowledgments**

### **Core Contributors**

-   **DiveForge Community**: Open source developers and diving professionals worldwide
-   **Certification Agencies**: PADI, SSI, TDI/SDI, NAUI, BSAC, GUE, IANTD, RAID for collaboration
-   **Dive Shop Partners**: Beta testing and feedback from dive operations globally
-   **Open Source Community**: Libraries and frameworks that make DiveForge possible

### **Special Thanks**

-   **Free Software Foundation**: For the GPL v3 license framework
-   **Diving Industry Professionals**: Subject matter expertise and standards guidance
-   **Beta Testing Dive Shops**: Real-world validation and improvement suggestions

---

## 📞 **Support**

### **Community Support**

-   **GitHub Issues**: [Report bugs and request features](https://github.com/diveforge/diveforge/issues)
-   **Community Forum**: [Get help from other users](https://github.com/diveforge/diveforge/discussions)
-   **Documentation**: [Comprehensive guides and tutorials](docs/)

### **Commercial Support**

Professional support available through certified DiveForge partners for:

-   Custom agency integrations
-   Enterprise deployment assistance
-   Training and consultation services
-   Priority feature development

---

## 🔗 **Links**

-   **Website**: [diveforge.org](https://diveforge.org)
-   **Documentation**: [docs.diveforge.org](https://docs.diveforge.org)
-   **Community**: [community.diveforge.org](https://community.diveforge.org)
-   **API Reference**: [api.diveforge.org](https://api.diveforge.org)

---

<div align="center">

**🌊 Dive into the Future of Dive Shop Management 🌊**

_Made with ❤️ by the diving community, for the diving community_

[⭐ Star this repository](https://github.com/diveforge/diveforge) | [🤝 Contribute](CONTRIBUTING.md) | [📖 Documentation](docs/) | [💬 Community](https://discord.gg/diveforge)

</div>
