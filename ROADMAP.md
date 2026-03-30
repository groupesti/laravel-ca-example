# Roadmap

## v0.1.0 — Initial Release

- [x] ExampleServiceProvider with config and command registration
- [x] Demo Root CA seeder
- [x] Demo Intermediate CA seeder
- [x] Demo certificate seeder (TLS, mTLS, code signing, S/MIME)
- [x] Full PKI seeder orchestrator
- [x] `ca-example:setup` command
- [x] `ca-example:reset` command
- [x] `ca-example:demo` interactive command
- [x] CertificateAuthorityFactory (immutable builder)
- [x] CertificateFactory with static constructors
- [x] KeyPairFactory with algorithm presets
- [x] CsrFactory
- [x] InternalPkiConfig sample
- [x] WebTlsConfig sample
- [x] MtlsConfig sample
- [x] CodeSigningConfig sample
- [x] LogCertificateIssued example listener
- [x] NotifyCaCreated example listener
- [x] DemoConfiguration readonly DTO
- [x] Unit tests for CertificateAuthorityFactory

## v0.2.0 — Planned

- [ ] Integration tests with actual CA package operations
- [ ] S/MIME sample configuration class
- [ ] CRL generation demo in `ca-example:demo`
- [ ] OCSP responder demo in `ca-example:demo`
- [ ] PKCS#12 export demo in `ca-example:demo`
- [ ] Additional factory unit tests (CertificateFactory, KeyPairFactory, CsrFactory)
- [ ] Seeder integration tests

## v1.0.0 — Stable Release

- [ ] Full integration with all CA packages
- [ ] Complete test coverage (>90%)
- [ ] Comprehensive documentation with tutorials
- [ ] Docker-based demo environment
- [ ] Interactive web-based demo (via laravel-ca-ui)

## Ideas / Backlog

- Postman/Insomnia collection for API demos
- Video tutorials for common workflows
- Template project generator (`ca-example:scaffold`)
- Performance benchmarking examples
- Multi-tenant CA demo scenario
