# DiveForge 🌊

**The First Universal Open Source Dive Shop Management Platform**

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![Version](https://img.shields.io/badge/Version-1.0.0-green.svg)]()
[![Agencies](https://img.shields.io/badge/Agencies-20+-orange.svg)]()
[![Community](https://img.shields.io/badge/Community-Driven-purple.svg)]()

DiveForge is a revolutionary GPL v3 licensed dive shop management application that supports **ALL major diving certification agencies** through a unified, enterprise-grade platform. Built with community collaboration at its core, DiveForge breaks down artificial barriers between certification agencies while maintaining the highest standards of security, compliance, and user experience.

---

## 🌊 **Why DiveForge?**

**🚀 Universal Agency Support**  
First platform to support PADI, SSI, TDI/SDI, NAUI, BSAC, GUE, IANTD, RAID, and 20+ additional agencies in one unified system.

**💯 Open Source Freedom**  
GPL v3 licensing ensures community ownership, preventing vendor lock-in and encouraging collaborative development.

**🏢 Enterprise Ready**  
Production-grade security with PCI DSS compliance, comprehensive audit logging, and multi-tenant capabilities.

**🔄 Easy Migration**  
Seamless transition from existing systems including DiveShop360.biz with automated data import and API compatibility.

**🔧 Modular Architecture**  
Plugin-based system allowing custom extensions and community-driven enhancements.

---

## 🏗️ **Key Features**

### **Multi-Agency Certification Management**
- **Universal Certification Support**: All major diving agencies in one platform
- **Cross-Agency Equivalencies**: Automatic certification equivalency calculations
- **Agency-Specific Compliance**: Individual agency standards and requirements validation
- **Unified Student Records**: Single customer profile across all certification bodies
- **Multi-Agency Course Management**: Complete course catalogs with standards compliance

### **Enterprise Business Operations**
- **Equipment Management**: Comprehensive inventory tracking with maintenance scheduling
- **Booking System**: Dive trips, courses, and equipment rentals with multi-agency support
- **Customer Portal**: Self-service access to certifications, receipts, and course progress
- **Financial Management**: Multi-currency support, payment processing, and comprehensive reporting
- **Safety Compliance**: Equipment safety tracking, incident reporting, and emergency procedures

### **Technical Excellence**
- **Web-Based Installation**: 6-step wizard with automatic database configuration
- **Multi-Database Support**: PostgreSQL, MySQL, SQLite, and SQL Server compatibility
- **Security First**: OAuth 2.0, multi-factor authentication, and encrypted data storage
- **Progressive Web App**: Mobile-responsive interface with offline capabilities
- **API-First Design**: RESTful APIs with OpenAPI documentation

---

## 🚀 **Quick Start**

### **Prerequisites**
- Node.js 18+ or PHP 8.1+
- Database (PostgreSQL recommended, MySQL, SQLite supported)
- Web server (Apache, Nginx, or built-in development server)

### **Installation**

1. **Clone the Repository**
```bash
git clone https://github.com/diveforge/diveforge.git
cd diveforge
```

2. **Start Installation Wizard**
```bash
# Using Node.js
npm install
npm run install:wizard

# Using PHP
composer install
php artisan diveforge:install

# Using Docker
docker-compose up -d
```

3. **Access Installation Wizard**
Open your browser to `http://localhost:3000/install`

### **Installation Steps**
1. **Welcome** - Review system requirements
2. **Database** - Configure your preferred database
3. **Admin Account** - Create administrator credentials
4. **Shop Setup** - Configure dive shop details and agency preferences
5. **Migration** - Import data from existing systems (optional)
6. **Complete** - Finalize installation and access dashboard

---

## 🌐 **Supported Certification Agencies**

### **Major International Agencies**
| Agency | Full Name | Specialty |
|--------|-----------|-----------|
| **PADI** | Professional Association of Diving Instructors | Recreational diving worldwide |
| **SSI** | Scuba Schools International | Digital-first training approach |
| **TDI** | Technical Diving International | Technical diving specialists |
| **SDI** | Scuba Diving International | Recreational diving |
| **NAUI** | National Association of Underwater Instructors | Flexible training standards |
| **BSAC** | British Sub-Aqua Club | UK-based club diving |
| **GUE** | Global Underwater Explorers | Technical team diving |
| **IANTD** | International Association of Nitrox and Technical Divers | Technical diving |
| **RAID** | Rebreather Association of International Divers | Rebreather and technical |

### **Additional Supported Agencies**
CMAS, UTD, PSAI, ACUC, IDEA, and 10+ regional certification bodies

---

## 📊 **Architecture Overview**

### **Core Components**
```
DiveForge/
├── 🔧 Core Engine/           # Universal certification processing
├── 🏢 Agency Adapters/       # Individual agency integrations
├── 💾 Database Layer/        # Multi-database abstraction
├── 🔐 Security Framework/    # Authentication & authorization
├── 🌐 Web Interface/         # Progressive web application
├── 📱 Customer Portal/       # Self-service customer access
├── 🔌 Plugin System/         # Community extensions
└── 📊 Reporting Engine/      # Analytics and compliance
```

### **Technology Stack**
- **Backend**: Node.js/Express.js or PHP/Laravel
- **Frontend**: React with Progressive Web App capabilities
- **Database**: PostgreSQL (primary), MySQL, SQLite, SQL Server
- **Authentication**: OAuth 2.0/OpenID Connect with MFA
- **Container**: Docker with Kubernetes orchestration
- **Security**: TLS 1.3, AES-256-GCM encryption

---

## 🛠️ **Development Setup**

### **Local Development**
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

### **Development Database**
```bash
# Quick SQLite setup for development
npm run db:setup:sqlite

# Or use PostgreSQL
npm run db:setup:postgres
```

### **Running Tests**
```bash
npm test                    # Unit tests
npm run test:integration   # Integration tests
npm run test:agencies      # Agency-specific tests
npm run test:e2e          # End-to-end tests
```

---

## 🔐 **Security & Compliance**

### **Security Features**
- **PCI DSS Compliance**: Secure payment processing and data storage
- **GDPR Compliant**: European data protection compliance
- **Multi-Factor Authentication**: Required for instructors and administrators
- **Encrypted Storage**: AES-256-GCM encryption for sensitive data
- **Audit Logging**: Comprehensive tracking of all system activities

### **Agency Compliance**
- **Universal Standards Validation**: Automatic compliance checking for all agencies
- **Instructor Qualification Tracking**: Multi-agency instructor certification monitoring
- **Equipment Safety Standards**: Agency-specific equipment requirements
- **Student-to-Instructor Ratios**: Automatic validation per agency standards

---

## 🤝 **Contributing**

DiveForge thrives on community contributions! We welcome developers, dive professionals, and diving enthusiasts.

### **How to Contribute**
1. **Fork** the repository
2. **Create** a feature branch (`git checkout -b feature/amazing-feature`)
3. **Commit** your changes (`git commit -m 'Add amazing feature'`)
4. **Push** to the branch (`git push origin feature/amazing-feature`)
5. **Open** a Pull Request

### **Contribution Areas**
- **Agency Integrations**: Add support for additional certification agencies
- **Equipment Modules**: Expand equipment tracking capabilities
- **Language Support**: Internationalization and localization
- **Mobile Features**: Enhanced mobile functionality
- **Plugin Development**: Community plugins and extensions

### **Development Guidelines**
- Follow GPL v3 licensing requirements
- Include unit tests for new features
- Update documentation for API changes
- Respect agency-specific standards and requirements

---

## 📖 **Documentation**

### **User Documentation**
- [Installation Guide](docs/installation/README.md)
- [User Manual](docs/users/README.md)
- [Customer Portal Guide](docs/portal/README.md)
- [Agency Integration Setup](docs/agencies/README.md)

### **Developer Documentation**
- [API Documentation](docs/api/README.md)
- [Plugin Development](docs/plugins/README.md)
- [Database Schema](docs/database/README.md)
- [Security Implementation](docs/security/README.md)

### **Administrator Documentation**
- [System Administration](docs/admin/README.md)
- [Backup & Recovery](docs/backup/README.md)
- [Performance Tuning](docs/performance/README.md)
- [Compliance Management](docs/compliance/README.md)

---

## 🌍 **Community**

### **Get Involved**
- **GitHub Discussions**: [Community Forum](https://github.com/diveforge/diveforge/discussions)
- **Discord**: [DiveForge Community](https://discord.gg/diveforge)
- **Monthly Meetings**: First Tuesday of each month
- **Contributor Sprints**: Quarterly development sessions

### **Community Resources**
- **Plugin Marketplace**: Community-developed extensions
- **Knowledge Base**: Shared expertise and best practices
- **Agency Partnerships**: Direct collaboration with certification bodies
- **Training Materials**: Open source dive shop management education

---

## 📊 **Roadmap**

### **Current Version: 1.0.0**
- ✅ Universal multi-agency support
- ✅ Web-based installation wizard
- ✅ DiveShop360.biz migration tools
- ✅ Customer portal with certification display
- ✅ Equipment management and tracking

### **Version 1.1.0 (Q2 2025)**
- 🔄 Enhanced mobile application
- 🔄 Advanced reporting and analytics
- 🔄 Equipment maintenance scheduling
- 🔄 Multi-language support (Spanish, French, German)

### **Version 1.2.0 (Q3 2025)**
- 📋 Integration with dive computer manufacturers
- 📋 Advanced certification pathway recommendations
- 📋 Equipment marketplace integration
- 📋 Instructor scheduling optimization

### **Version 2.0.0 (Q4 2025)**
- 📋 AI-powered customer insights
- 📋 Advanced safety prediction algorithms
- 📋 Blockchain certification verification
- 📋 Global dive site integration

---

## 🏆 **Success Stories**

*"DiveForge transformed our multi-agency dive shop operations. We now seamlessly handle PADI, SSI, and TDI certifications in one system, saving us hours of administrative work daily."*  
**— Marina Santos, Blue Ocean Diving Center**

*"The open source nature allowed us to customize DiveForge for our technical diving focus while maintaining compatibility with all major agencies."*  
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
- **DiveForge Community**: Open source developers and diving professionals worldwide
- **Certification Agencies**: PADI, SSI, TDI/SDI, NAUI, BSAC, GUE, IANTD, RAID for collaboration
- **Dive Shop Partners**: Beta testing and feedback from dive operations globally
- **Open Source Community**: Libraries and frameworks that make DiveForge possible

### **Special Thanks**
- **Free Software Foundation**: For the GPL v3 license framework
- **Diving Industry Professionals**: Subject matter expertise and standards guidance
- **Beta Testing Dive Shops**: Real-world validation and improvement suggestions

---

## 📞 **Support**

### **Community Support**
- **GitHub Issues**: [Report bugs and request features](https://github.com/diveforge/diveforge/issues)
- **Community Forum**: [Get help from other users](https://github.com/diveforge/diveforge/discussions)
- **Documentation**: [Comprehensive guides and tutorials](docs/)

### **Commercial Support**
Professional support available through certified DiveForge partners for:
- Custom agency integrations
- Enterprise deployment assistance
- Training and consultation services
- Priority feature development

---

## 🔗 **Links**

- **Website**: [diveforge.org](https://diveforge.org)
- **Documentation**: [docs.diveforge.org](https://docs.diveforge.org)
- **Community**: [community.diveforge.org](https://community.diveforge.org)
- **API Reference**: [api.diveforge.org](https://api.diveforge.org)

---

<div align="center">

**🌊 Dive into the Future of Dive Shop Management 🌊**

*Made with ❤️ by the diving community, for the diving community*

[⭐ Star this repository](https://github.com/diveforge/diveforge) | [🤝 Contribute](CONTRIBUTING.md) | [📖 Documentation](docs/) | [💬 Community](https://discord.gg/diveforge)

</div>
