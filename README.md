# Fake Twitter
 rendu CDI

BRACHET ANTOINE


le code de tout les user est 123

si vous voulez récrée la base de donnée:


INSERT INTO users (username, password, email, is_admin, created_at) VALUES
('adminUser', '$2y$10$6c1WTk99kAMaF87sybOzVO.PJVm.HMGcpAAVMsq2VV9HA1FCbea0i', 'admin@example.com', 1, NOW()),  -- Admin user
('user1', '$2y$10$6c1WTk99kAMaF87sybOzVO.PJVm.HMGcpAAVMsq2VV9HA1FCbea0i', 'user1@example.com', 0, NOW()),
('user2', '$2y$10$6c1WTk99kAMaF87sybOzVO.PJVm.HMGcpAAVMsq2VV9HA1FCbea0i', 'user2@example.com', 0, NOW()),
('user3', '$2y$10$6c1WTk99kAMaF87sybOzVO.PJVm.HMGcpAAVMsq2VV9HA1FCbea0i', 'user3@example.com', 0, NOW()),
('user4', '$2y$10$6c1WTk99kAMaF87sybOzVO.PJVm.HMGcpAAVMsq2VV9HA1FCbea0i', 'user4@example.com', 0, NOW());

INSERT INTO articles (user_id, title, content, created_at) VALUES
(1, 'Introduction à l\'IA et l\'apprentissage automatique', 'Cet article explore les bases de l\'intelligence artificielle et comment l\'apprentissage automatique est utilisé pour résoudre des problèmes complexes.', NOW()),
(2, 'Les tendances de la technologie pour 2024', 'Un aperçu des technologies émergentes, y compris la 5G, les métavers, et les solutions écologiques.', NOW()),
(3, 'Comment sécuriser son compte en ligne', 'Des astuces pratiques pour protéger vos comptes en ligne, y compris l\'utilisation de mots de passe sécurisés et l\'authentification à deux facteurs.', NOW()),
(4, 'Les avantages du télétravail', 'Avec la montée en puissance du télétravail, découvrez ses avantages et ses inconvénients pour les employés et les employeurs.', NOW()),
(5, 'La montée en puissance de la réalité augmentée', 'La réalité augmentée révolutionne les industries, de la vente au détail à la formation en entreprise. Cet article explore ses diverses applications.', NOW());
