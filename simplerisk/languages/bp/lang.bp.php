<?php
/***********************************************************************
 * This Source Code Form is subject to the terms of the Mozilla Public *
 * License, v. 2.0. If a copy of the MPL was not distributed with this *
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.            *
 ***********************************************************************/

ini_set('default_charset', 'utf-8');
define('DATETIME', 'd/m/Y g:i A T');
define('DATETIMESIMPLE', 'd/m/Y H:i');
define('DATESIMPLE', 'd/m/Y');

$lang = array(
    'Home'=>'Início',
    'RiskManagement'=>'Gestão de Riscos',
    'Reporting'=>'Relatórios',
    'Configure'=>'Configurar',
    'MyProfile'=>'Meu Perfil',
    'Logout'=>'Sair',
    'LogInHere'=>'Acesse Aqui',
    'Username'=>'Usuário',
    'Password'=>'Senha',
    'ForgotYourPassword'=>'Esqueceu sua senha',
    'Login'=>'Entrar',
    'Reset'=>'Limpar',
    'Send'=>'Enviar',
    'Update'=>'Atualizar',
    'SendPasswordResetEmail'=>'Enviar Nova Senha por E-mail',
    'PasswordReset'=>'Nova Senha',
    'ResetToken'=>'Token',
    'RepeatPassword'=>'Repetir Senha',
    'Submit'=>'Enviar',
    'ProfileDetails'=>'Detalhes do Perfil',
    'LastLogin'=>'Último Acesso',
    'ChangePassword'=>'Alterar Senha',
    'CurrentPassword'=>'Senha Atual',
    'NewPassword'=>'Nova Senha',
    'ConfirmPassword'=>'Confirme sua Senha',
    'ConfigureRiskFormula'=>'Configurar Fórmula de Risco',
    'ConfigureReviewSettings'=>'Configurar Opções de Revisão',
    'AddAndRemoveValues'=>'Adicionar e Remover Valores',
    'UserManagement'=>'Gestão de Usuários',
    'RedefineNamingConventions'=>'Regras de Nomenclatura',
    'AuditTrail'=>'Auditoria',
    'Extras'=>'Extras',
    'Announcements'=>'Anúncios',
    'About'=>'Sobre',
    'Impact'=>'Impacto',
    'Likelihood'=>'Probabilidade',
    'MitigationEffort'=>'Mitigação de Esforços',
    'Change'=>'Alterar',
    'to'=>'para',
    'AddANewUser'=>'Adicionar um Novo Usuário',
    'Type'=>'Tipo',
    'FullName'=>'Nome Completo',
    'EmailAddress'=>'Endereço de E-mail',
    'Teams'=>'Equipe(s)',
    'ALL'=>'TODOS',
    'NONE'=>'NENHUM',
    'UserResponsibilities'=>'Responsabilidades do Usuário',
    'AbleToSubmitNewRisks'=>'Autorizado para Acrescentar Novos Riscos',
    'AbleToModifyExistingRisks'=>'Autorizado para Modificar Riscos Existentes',
    'AbleToCloseRisks'=>'Autorizado para Encerrar Riscos',
    'AbleToPlanMitigations'=>'Autorizado para Planejar Mitigações',
    'AbleToReviewLowRisks'=>'Autorizado para Rever Baixos Riscos',
    'AbleToReviewMediumRisks'=>'Autorizado para Rever Médios Riscos',
    'AbleToReviewHighRisks'=>'Autorizado para Rever Altos Riscos',
    'AllowAccessToConfigureMenu'=>'Permitir Acesso ao Menu Configurar',
    'MultiFactorAuthentication'=>'Autenticação de Múltiplos Fatores',
    'None'=>'Nenhum',
    'Add'=>'Adicionar',
    'ViewDetailsForUser'=>'Ver os Detalhes para o Usuário',
    'DetailsForUser'=>'Detalhes para o Usuário',
    'Select'=>'Selecionar',
    'EnableAndDisableUsers'=>'Ativar e Desativar Usuários',
    'EnableAndDisableUsersHelp'=>'Use esse recurso para ativar ou desativar os logins de usuários, mantendo a sequência de auditoria das atividades do usuário',
    'DisableUser'=>'Desativar usuário',
    'Disable'=>'Desabilitar',
    'EnableUser'=>'Habilitar usuário',
    'Enable'=>'Habilitar',
    'DeleteAnExistingUser'=>'Excluir um Usuário Existente',
    'DeleteCurrentUser'=>'Excluir usuário atual',
    'Delete'=>'Excluir',
    'SendPasswordResetEmailForUser'=>'Enviar e-mail de redefinição de senha para o usuário',
    'Category'=>'Categoria',
    'AddNewCategoryNamed'=>'Adicionar nova categoria chamada',
    'DeleteCurrentCategoryNamed'=>'Excluir a categoria atual chamada',
    'Team'=>'Equipe',
    'AddNewTeamNamed'=>'Adicionar nova equipe chamada',
    'DeleteCurrentTeamNamed'=>'Excluir a equipe atual chamada',
    'Technology'=>'Tecnologia',
    'AddNewTechnologyNamed'=>'Adicionar nova tecnologia chamada',
    'DeleteCurrentTechnologyNamed'=>'Excluir a tecnologia atual chamada',
    'SiteLocation'=>'Site/Localização',
    'AddNewSiteLocationNamed'=>'Adicionar novo site/localização chamado',
    'DeleteCurrentSiteLocationNamed'=>'Excluir o site/localização atual chamado',
    'ControlRegulation'=>'Controle de Regulamentação',
    'AddNewControlRegulationNamed'=>'Adicionar novo controle de regulamentação chamado',
    'DeleteCurrentControlRegulationNamed'=>'Excluir o controle de regulamentação atual chamado',
    'RiskPlanningStrategy'=>'Estratégia de Planejamento de Riscos',
    'AddNewRiskPlanningStrategyNamed'=>'Adicionar nova estratégia de planejamento de riscos chamada',
    'DeleteCurrentRiskPlanningStrategyNamed'=>'Excluir a estratégia de planejamento de riscos atual chamada',
    'CloseReason'=>'Motivo de Encerramento',
    'AddNewCloseReasonNamed'=>'Adicionar novo motivo de encerramento chamado',
    'DeleteCurrentCloseReasonNamed'=>'Excluir o motivo de encerramento atual chamado',
    'IWantToReviewHighRiskEvery'=>'Quero rever ALTO Risco a cada',
    'IWantToReviewMediumRiskEvery'=>'Quero rever MÉDIO Risco a cada',
    'IWantToReviewLowRiskEvery'=>'Quero rever BAIXO Risco a cada',
    'days'=>'dias',
    'MyClassicRiskFormulaIs'=>'Minha Clássica Fórmula de Risco É',
    'RISK'=>'RISCO',
    'IConsiderHighRiskToBeAnythingGreaterThan'=>'Considero ALTO risco de ser algo maior do que',
    'IConsiderMediumRiskToBeLessThanAboveButGreaterThan'=>'Considero risco MÉDIO a ser menos do que acima, mas maior do que',
    'IConsiderlowRiskToBeLessThanAboveButGreaterThan'=>'Considero BAIXO risco de ser menos do que acima, mas maior do que',
    'HighRisk'=>'Alto Risco',
    'MediumRisk'=>'Médio Risco',
    'LowRisk'=>'Baixo Risco',
    'Irrelevant'=>'Irrelevante',
    'SubmitYourRisks'=>'Envie Seus Riscos',
    'PlanYourMitigations'=>'Planeje suas Mitigações',
    'PerformManagementReviews'=>'Realizar Revisões de Gestão',
    'PrioritizeForProjectPlanning'=>'Priorizar para Planejamento de Projetos',
    'ReviewRisksRegularly'=>'Revisar Riscos Regularmente',
    'DocumentANewRisk'=>'Documentar um Novo Risco',
    'UseThisFormHelp'=>'Use este formulário para documentar um novo risco para consideração no Processo de Gestão de Risco',
    'Subject'=>'Assunto',
    'ExternalReferenceId'=>'ID de Referência Externa',
    'ControlNumber'=>'Número de controle',
    'Owner'=>'Proprietário',
    'OwnersManager'=>'Gerente do Proprietário',
    'RiskScoringMethod'=>'Método de Pontuação de Risco',
    'CurrentLikelihood'=>'Probabilidade Atual',
    'CurrentImpact'=>'Impacto Atual',
    'RiskAssessment'=>'Avaliação de Risco',
    'AdditionalNotes'=>'Notas Adicionais',
    'UNREVIEWED'=>'NÃO REVISADO',
    'PASTDUE'=>'VENCIDO',
    'ID'=>'ID',
    'Status'=>'Estado',
    'Risk'=>'Risco',
    'DaysOpen'=>'Dias Aberto',
    'CalculatedRisk'=>'Risco Calculado',
    'SubmittedBy'=>'Enviado Por',
    'NextReviewDate'=>'Próxima Avaliação',
    'CVSSRiskScoring'=>'Pontuação de Risco CVSS',
    'DREADRiskScoring'=>'Pontuação de Risco DREAD',
    'OWASPRiskScoring'=>'Pontuação de Risco OWASP',
    'CustomRiskScoring'=>'Pontuação de Risco Custom',
    'MitigationPlanningHelp'=>'Abaixo está a lista dos riscos apresentados que requerem um planejamento de mitigação',
    'ManagementReviewHelp'=>'Abaixo está a lista dos riscos apresentados que requerem uma análise de gestão',
    'Submitted'=>'Enviado',
    'MitigationPlanned'=>'Mitigação Planejada',
    'ManagementReview'=>'Revisão de Gestão',
    'No'=>'Não',
    'Yes'=>'Sim',
    'AddAndRemoveProjects'=>'Adicionar e Remover Projetos',
    'AddAndRemoveProjectsHelp'=>'Adicionar e remover os projetos, a fim de associar vários riscos em conjunto para a priorização',
    'AddNewProjectNamed'=>'Adicionar novo projeto chamado',
    'DeleteCurrentProjectNamed'=>'Excluir o projeto atual chamado',
    'AddUnassignedRisksToProjects'=>'Adicionar Riscos Não Atribuídos para Projetos',
    'AddUnassignedRisksToProjectsHelp'=>'Arraste e solte os riscos não atribuídos marcados para se considerar um projeto na guia de projeto apropriado',
    'PrioritizeProjects'=>'Priorizar Projetos',
    'PrioritizeProjectsHelp'=>'Mova os projetos ao redor e altere a ordem de priorização. Uma vez terminado, não se esqueça de clicar no botão "Atualizar" para salvar as alterações',
    'SaveRisksToProjects'=>'Salvar Riscos para Projetos',
    'RiskId'=>'Risco ID',
    'RiskActions'=>'Ações de Risco',
    'ReopenRisk'=>'Reabrir Risco',
    'CloseRisk'=>'Encerrar Risco',
    'EditRisk'=>'Editar Risco',
    'PlanAMitigation'=>'Planejar uma Mitigação',
    'PerformAReview'=>'Realizar uma Revisão',
    'AddAComment'=>'Adicione um Comentário',
    'ShowRiskScoringDetails'=>'Mostrar Detalhes da Pontuação de Risco',
    'HideRiskScoringDetails'=>'Ocultar Detalhes da Pontuação de Risco',
    'Details'=>'Detalhes',
    'SubmissionDate'=>'Data de Envio',
    'DateSubmitted'=>'Data de Envio',
    'EditDetails'=>'Editar Detalhes',
    'Mitigation'=>'Mitigação',
    'MitigationDate'=>'Data da Mitigação',
    'PlanningStrategy'=>'Estratégia de Planejamento ',
    'CurrentSolution'=>'Solução Atual',
    'SecurityRequirements'=>'Requisitos de Segurança',
    'SecurityRecommendations'=>'Recomendações de Segurança',
    'EditMitigation'=>'Editar Mitigação',
    'LastReview'=>'Última Revisão',
    'ReviewDate'=>'Data da Revisão',
    'Reviewer'=>'Crítico',
    'Review'=>'Revisão',
    'NextStep'=>'Próxima Passo',
    'Comments'=>'Comentários',
    'ViewAllReviews'=>'Ver Todas as Revisões',
    'ReviewHistory'=>'Histórico de Revisão',
    'Comment'=>'Comentário',
    'ClassicRiskScoring'=>'Risco de Pontuação Classic',
    'RiskScoringActions'=>'Ações de Pontuação de Risco',
    'UpdateClassicScore'=>'Atualizar Pontuação Clássica',
    'ScoreBy'=>'Pontuação por',
    'RISKClassicExp1'=>'RISCO = ( Probabilidade x Impacto + 2(Impacto) )',
    'RISKClassicExp2'=>'RISCO = ( Probabilidade x Impacto + Impacto )',
    'RISKClassicExp3'=>'RISCO = ( Probabilidade x Impacto )',
    'RISKClassicExp4'=>'RISCO = ( Probabilidade x Impacto + Probabilidade )',
    'RISKClassicExp5'=>'RISCO = ( Probabilidade x Impacto + 2(Probabilidade) )',
    'Reason'=>'Motivo',
    'CloseOutInformation'=>'Informação de Encerramento',
    'SubmitManagementReview'=>'Enviar Revisão de Gestão',
    'SubmitRiskMitigation'=>'Enviar Mitigação de Risco',
    'RiskDashboard'=>'Painel de Riscos',
    'RiskTrend'=>'Tendência de Risco',
    'AllOpenRisksAssignedToMeByRiskLevel'=>'Todos os Riscos Abertos Atribuídos a Mim por Nível de Risco',
    'AllOpenRisksByRiskLevel'=>'Todos os Riscos Abertos por Nível de Risco',
    'AllOpenRisksConsideredForProjectsByRiskLevel'=>'Todos os Riscos Considerados Abertos como Projetos por Nível de Risco',
    'AllOpenRisksAcceptedUntilNextReviewByRiskLevel'=>'Todos os Riscos Abertos Aceitos até a Próxima Revisão por Nível de Risco',
    'AllOpenRisksToSubmitAsAProductionIssueByRiskLevel'=>'Todos os Riscos Apresentados como Questão de Produção por Nível de Risco',
    'AllOpenRisksByScoringMethod'=>'Todos os Riscos Abertos por Método de Pontuação',
    'AllClosedRisksByRiskLevel'=>'Todos os Riscos Encerrados por Nível de Risco ',
    'SubmittedRisksByDate'=>'Riscos Enviados por Data',
    'MitigationsByDate'=>'Mitigações por Data',
    'ManagementReviewsByDate'=>'Revisões de Gestão por Data',
    'ProjectsAndRisksAssigned'=>'Projetos e Riscos Atribuídos',
    'OpenRisks'=>'Riscos Abertos',
    'ClosedRisks'=>'Riscos Encerrados',
    'ReportMyOpenHelp'=>'Este relatório mostra todos os riscos abertos que o usuário atual tem como o proprietário ou gerente associado ao risco ordenado por nível de risco',
    'ReportOpenHelp'=>'Este relatório mostra todos os riscos abertos ordenados por nível de risco',
    'ReportProjectsHelp'=>'Este relatório mostra todos os riscos considerados abertos como projetos ordenados por nível de risco',
    'ReportNextReviewHelp'=>'Este relatório mostra todos os riscos abertos aceitos até a próxima revisão ordenados por nível de risco',
    'ReportProductionIssuesHelp'=>'Este relatório mostra todos os riscos abertos apresentados como problemas de produção ordenados por nível de risco',
    'ReportRiskScoringHelp'=>'Este relatório mostra todos os métodos de pontuação de risco e os riscos registrados com cada',
    'ReportClosedHelp'=>'Este relatório mostra todos os riscos fechados ordenados por nível de risco',
    'ReportSubmittedByDateHelp'=>'Este relatório mostra todos os riscos ordenados por data de apresentação',
    'ReportMitigationsByDateHelp'=>'Este relatório mostra todas as mitigações planejadas ordenados por data de mitigação',
    'ReportMgmtReviewsByDateHelp'=>'Este relatório mostra todas revisões de gestão ordenadas por data de revisão',
    'ReportProjectsAndRisksHelp'=>'Este relatório mostra todos os projetos e os riscos que lhes são atribuídos',
    'Language'=>'Língua',
    'AllOpenRisksNeedingReview'=>'Todos os Riscos Abertos Requerem de uma Revisão',
    'ReportReviewNeededHelp'=>'Este relatório mostra todos os riscos abertoas que requerem uma análise de gestão',
    'CustomValue'=>'Valor Personalizado',
    'ClosedRisksByDate'=>'Riscos Encerrados por Data',
    'DateClosed'=>'Data de Encerrado',
    'ClosedBy'=>'Encerrado Por',
    'ReportClosedByDateHelp'=>'Este relatório mostra todos os riscos ordenados por data de encerrado',
    'AllOpenRisksByTeam'=>'Todos os Riscos Abertos por Equipe',
    'ReportRiskTeamsHelp'=>'Este relatório mostra todos os métodos de equipe',
    'Unassigned'=>'Não Atribuídos',
    'AllOpenRisksByTechnology'=>'Todos os Riscos Abertos por Technologia',
    'ReportRiskTechnologiesHelp'=>'Este relatório mostra todos os métodos de technologia',
    'RiskLevel'=>'Nível de Risco',
    'BasedOnTheCurrentRiskScore'=>'Com base na classificação de risco atual, a próxima data de revisão será ',
    'WouldYouLikeToUseADifferentDate'=>'Gostaria de usar uma data diferente em vez disso?',
    'RisksOpenedAndClosedOverTime'=>'Aberta e Fechada Riscos ao Longo do Tempo',
    'AllRiskScoresAreAdjusted'=>'Todos os escores de risco são ajustados para caber em uma escala de 0-10.',
    'DetermineProjectStatus'=>'Determinar Status do Projeto',
    'ProjectStatusHelp'=>'Coloque projetos em baldes com base em seu status atual.',
    'ActiveProjects'=>'Projetos Ativos',
    'OnHoldProjects'=>'Projetos Pausados',
    'CompletedProjects'=>'Projetos Concluídos',
    'CancelledProjects'=>'Projetos Cancelados',
    'UpdateProjectStatuses'=>'Status das Atualizações do Projeto',
    'HighRiskReport'=>'Relatório de Alto Risco',
    'TotalOpenRisks'=>'Total de Riscos Abertos',
    'TotalHighRisks'=>'Total de Riscos Altos',
    'HighRiskPercentage'=>'Percentagem de Alto Risco',
    'UpdateClassicScore'=>'Atualizar Pontuação clássico',
    'CurrentLikelihood'=>'Probabilidade Atual',
    'CurrentImpact'=>'Impacto Atual',   
    'UpdateCVSSScore'=>'Atualizar Pontuação CVSS',
    'BaseScoreMetrics'=>'Métricas de Pontuação Base',
    'AttackVector'=>'Vetor de Ataque',
    'AttackComplexity'=>'Complexidade Ataque',
    'Authentication'=>'Autenticação',
    'ConfidentialityImpact'=>'Confidencialidade de Impacto',
    'IntegrityImpact'=>'Integridade de Impacto',
    'AvailabilityImpact'=>'Disponibilidade de Impacto',
    'TemporalScoreMetrics'=>'Métricas de Pontuação Temporal',
    'Exploitability'=>'Explorabilidade',
    'RemediationLevel'=>'Nível de Remediação',
    'ReportConfidence'=>'Relatório de Confiança',
    'EnvironmentalScoreMetrics'=>'Métricas de Pontuação Ambiental',
    'CollateralDamagePotential'=>'Potencial Dano Collateral',
    'TargetDistribution'=>'Alvo de Distribuição',
    'ConfidentialityRequirement'=>'Confidencialidade de Requisito',
    'IntegrityRequirement'=>'Integridade de Requisito',
    'AvailabilityRequirement'=>'Disponibilidade de Requisito',
    'UpdateDREADScore'=>'Atualizar Pontuação DREAD',
    'DamagePotential'=>'Nível de Risco',
    'Reproducibility'=>'Reprodutibilidade',
    'AffectedUsers'=>'Usuários Afetados',
    'Discoverability'=>'Descoberta',
    'UpdateOWASPScore'=>'Atualizar Pontuação OWASP',
    'ThreatAgentFactors'=>'Ameaça de Fatores Agente',
    'SkillLevel'=>'Nível de Habilidade',
    'Motive'=>'Motivo',
    'Opportunity'=>'Oportunidade',
    'Size'=>'Tamanho',
    'VulnerabilityFactors'=>'Fatores de Vulnerabilidade',
    'EaseOfDiscovery'=>'Facilidade de Descoberta',
    'EaseOfExploit'=>'Facilidade de Exploração',
    'Awareness'=>'Consciência',
    'IntrusionDetection'=>'Detecção de Intrusão',
    'TechnicalImpact'=>'Impacto Técnico',
    'LossOfConfidentiality'=>'Perda de Confidencialidade',
    'LossOfIntegrity'=>'Perda de Integridade',
    'LossOfAvailability'=>'Perda de Disponibilidade',
    'LossOfAccountability'=>'Perda de Responsabilidade',
    'BusinessImpact'=>'Impacto nos Negócios',
    'FinancialDamage'=>'Prejuízos Financeiros',
    'ReputationDamage'=>'Prejudicar de Reputação',
    'NonCompliance'=>'Não-Conformidade',
    'PrivacyViolation'=>'Violação de Privacidade',
    'UpdateCustomScore'=>'Atualizar Pontuação Personalizada',
    'ManuallyEnteredValue'=>'Valor Inserido Manualmente',
    'ScoreByClassic'=>'Pontuação por Clássico',
    'ScoreByCVSS'=>'Pontuação por CVSS',
    'ScoreByDREAD'=>'Pontuação por DREAD',
    'ScoreByOWASP'=>'Pontuação por OWASP',
    'ScoreByCustom'=>'Pontuação por Customizado',
    'BaseVector'=>'Vector Base',
    'TemporalVector'=>'Vector Temporal',
    'EnvironmentalVector'=>'Vector Ambiental',
    'SupportingDocumentation'=>'Documentação de Apoio',
    'Import'=>'Importação',
    'Export'=>'Exportação',
    'ActivateTheImportExportExtra'=>'Ativar a Importação / Exportação extra',
    'PrintableView'=>'Visão de Impressão',
    'GroupBy'=>'Agrupar Por',
    'IncludedColumns'=>'Colunas Incluídas',
    'AllRisks'=>'Todos os Riscos',
    'DynamicRiskReport'=>'Relatório de Risco Dinâmico',
    'ObsoleteReports'=>'Relatórios Obsoletos',
    'Project'=>'Projeto',
    'SortBy'=>'Ordenar Por',
    'MonthSubmitted'=>'Mês Submetido',
    'AssetManagement'=>'Gestão de Ativos',
    'AutomatedDiscovery'=>'Descoberta Automatizada',
    'BatchImport'=>'Batch Import',
    'ManageAssets'=>'Gerenciar Ativos',
    'AssetValuation'=>'Avaliação Patrimonial',
    'AllowAccessToAssetManagementMenu'=>'Permitir acesso ao Menu "Gestão De Ativos"',
    'AutomatedDiscoveryHelp'=>'Descubra todos os endereços IP ao vivo na faixa de IP digitado. Os endereços IP ao vivo serão adicionados à lista de ativos. Formatos aceitáveis:',
    'IPRange'=>'IP Range',
    'Search'=>'Pesquisa',
    'AddANewAsset'=>'Adicionar um Novo Ativo',
    'DeleteAnExistingAsset'=>'Excluir um Ativo Existente',
    'AssetName'=>'Nome do Ativo',
    'IPAddress'=>'Endereço IP',
    'AssetWasAddedSuccessfully'=>'Ativo adicionado com sucessoo.',
    'AssetWasDeletedSuccessfully'=>'Ativo excluído com sucesso.',
    'ThereWasAProblemAddingTheAsset'=>'Houve um problema ao adicionar o ativo.',
    'ThereWasAProblemDeletingTheAsset'=>'Houve um problema a exclusão do ativo.',
    'ComingSoon'=>'Em Breve',
    'ExportRisks'=>'Exportação de Riscos',
    'ExportMitigations'=>'Exportação de Mitigações',
    'ExportReviews'=>'Exportação de Avaliações',
    'ExportCombined'=>'Exportação Combinada',
    'MitigatedBy'=>'Mitigado Por',
    'MitigationId'=>'ID de Mitigação',
    'ReviewId'=>'ID da Avaliação',
    'AffectedAssets'=>'Ativos Afetados',
    'Activate'=>'Ativar',
    'DeleteRisks'=>'Excluir Riscos',
    'DeletedRisksCannotBeRecovered'=>'Riscos Excluídos Não Podem Ser Recuperados',
    'RisksDeletedSuccessfully'=>'Riscos Excluídos Com Sucesso',
    'ThereWasAProblemDeletingTheRisk'=>'Houve Um Problema Excluindo Os Riscos',
    'Activated'=>'ATIVADO',
    'IWantToReviewInsignificantRiskEvery'=>'Quero rever INSIGNIFICANTE Risco a cada',
    'Insignificant'=>'Insignificante',
    'IConsiderVeryHighRiskToBeAnythingGreaterThan'=>'Considero MUITO ALTO risco de ser algo maior do que',
    'IConsiderHighRiskToBeLessThanAboveButGreaterThan'=>'Considero ALTO risco de ser menos do que acima, mas maior do que',
    'VeryHigh'=>'Muito Alto',
    'VeryHighRisk'=>'Muito Alto Risco',
    'IWantToReviewVeryHighRiskEvery'=> 'Quero rever MUITO ALTO Risco a cada',
    'AbleToReviewVeryHighRisks'=>'Autorizado para Rever Muito Altos Riscos',
    'AbleToReviewInsignificantRisks'=>'Autorizado para Rever Insignificante Riscos',
    'TotalVeryHighRisks'=>'Total de Riscos Muito Altos',
    'VeryHighRiskPercentage'=>'Percentagem de Muito Alto Risco',
    'AllTeams'=>'Todas as Equipes',
    'FileUploadSettings'=>'Configurações de Upload de Arquivos',
    'AllowedFileTypes'=>'Permitidos Tipos de Arquivo',
    'AddNewFileTypeOf'=>'Adicionar novo tipo de arquivo',
    'DeleteCurrentFileTypeOf'=>'Excluir tipo de arquivo atual',
    'MaximumUploadFileSize'=>'Tamanho Máximo de Carregamento de Arquivo',
    'Bytes'=>'Bytes',
    'CheckAll'=>'Verificar Tudo',
    'CheckAllRiskMgmt'=>'Marcar Tudo de Gestão de Risco',
    'CheckAllAssetMgmt'=>'Marcar Tudo de Gestão de Ativos',
    'CheckAllConfigure'=>'Marcar Tudo Configurar',
    'MitigationTeam'=>'A Atenuação da Equipe',
    'ImportRisks'=>'Importação Riscos',
    'ImportAssets'=>'Importação Ativos',
    'AssetValue'=>'Valor Ativos',
    'Register'=>'Registrar',
    'RegisterSimpleRisk'=>'Registrar SimpleRisk',
    'RegistrationText'=>'Ao registar-se SimpleRisk você estará fornecendo suas informações de contato para que você possa ser atualizado com as últimas informações de liberação e notificações de segurança importantes. As suas informações nunca serão vendidas a um terceiro. Casos registrados também têm a capacidade de fazer backup e atualizado com o clique de um botão.',
    'RegistrationInformation'=>'Informação de Registro',
    'Company'=>'Companhia',
    'JobTitle'=>'Cargo',
    'Phone'=>'Telefone',
    'UpgradeSimpleRisk'=>'Atualize SimpleRisk',
    'UpgradeInstructions'=>'Esta seção usa a Atualização Extra. Para se certificar de que você tem a versão mais recente, selecione "Atualizar", volte a registar, e recarregue esta página.',
    'NoUpgradeNeeded'=>'Nenhuma atualização é necessária neste momento.',
    'BackupDatabase'=>'Faça o Backup do Banco de Dados',
    'UpgradeApplication'=>'Atualizar o Aplicativo',
    'UpgradeDatabase'=>'Atualize o Banco de Dados',
    'CustomExtras'=>'Extras Personalizados',
    'CustomExtrasText'=>'Seria fantástico se tudo fosse livre, certo? Esperemos que a plataforma central SimpleRisk é capaz de servir todas as suas necessidades de gestão de risco. Mas, se você se encontrar ainda querendo mais funcionalidade, temos desenvolvido uma série de "Extras" que vai fazer exatamente isso.',
    'Upgrade'=>'Atualização',
    'Install'=>'Instalar',
    'Purchase'=>'Compra',
    'PasswordPolicy'=>'Política de Senha',
    'MinimumNumberOfCharacters'=>'Número Mínimo de Personagens',
    'RequireAlphaCharacter'=>'Exigir Caractere Alpha',
    'RequireUpperCaseCharacter'=>'Exigir Caractere Maiúsculas',
    'RequireLowerCaseCharacter'=>'Exigir Caractere Minúsculas',
    'RequireNumericCharacter'=>'Exigir Caractere Numérico',
    'RequireSpecialCharacter'=>'Exigir Caractere Especial',
    'Enabled'=>'Ativado',
    'RiskPyramid'=>'Pirâmide de Risco',
    'RiskPyramidDescription'=>'A pirâmide de risco acima ajuda a mostrar a distribuição dos riscos entre os vários níveis de risco. Uma pirâmide pesado top pode ser um sinal de que a sua organização está assumindo um risco muito grande.',
    'RiskAdvice'=>'Assessoria de Riscos',
    'AddDeleteAssets'=>'Adicionar e Excluir Ativos',
    'EditAssets'=>'Editar Ativos',
    'AutomaticAssetValuation'=>'Avaliação Patrimonial Automática',
    'ManualAssetValuation'=>'Avaliação Patrimonial Manual',
    'MinimumValue'=>'Valor Mínimo',
    'MaximumValue'=>'Valor Máximo',
    'ValueRange'=>'Intervalo de Valores',
    'DefaultAssetValuation'=>'Avaliação Patrimonial Padrão',
    'Default'=>'Padrão',
    'RisksAndAssets'=>'Riscos e Ativos',
    'Report'=>'Relatório',
    'RisksByAsset'=>'Riscos de Ativos',
    'AssetsByRisk'=>'Ativos de Risco',
    'MaximumQuantitativeLoss'=>'Perda Máxima Quantitative',
    'MitigationOwner'=>'Mitigação Proprietário',
    'MitigationCost'=>'Mitigação de Custos',
    'RiskColumns'=>'Risco Colunas',
    'MitigationColumns'=>'Mitigação Colunas',
    'ReviewColumns'=>'Comentário Colunas',
    'ChangeStatus'=>'Alterar Estado',
    'SetRiskStatusTo'=>'Definir Status de Risco Para',
    'AddNewStatusNamed'=>'Adicionar novo status nomeado',
    'DeleteStatusNamed'=>'Excluir estatuto nomeado',
    'DefaultCurrencySymbol'=>'Símbolo Monetário Padrão',
    'DefaultValues'=>'Valores Padrão',
    'RiskSource'=>'Fonte Risco',
    'AddNewSourceNamed'=>'Adicionar nova fonte nomeado',
    'DeleteSourceNamed'=>'Excluir fonte nomeado',
    'CheckAllAssessments'=>'Verifique Todas as Avaliações',
    'AllowAccessToAssessmentsMenu'=>'Permitir o Acesso a "Avaliações" Menu',
    'Assessments'=>'Avaliações',
    'AvailableAssessments'=>'As Avaliações Disponíveis',
    'PendingRisks'=>'Riscos Pendentes',
    'CreateAssessment'=>'Criar Avaliação',
    'EditAssessment'=>'Editar Avaliação',
    'Overview'=>'Visão Geral',
    'OpenVsClosed'=>'Aberto vs Encerrado',
    'MitigatedVsUnmitigated'=>'Mitigado vs Não Mitigado',
    'ReviewedVsUnreviewed'=>'Revisado vs Não Revisado',
    'OpenedRisks'=>'Riscos Abertas',
    'MailSettings'=>'Configurações de Mensagens',
    'TransportAgent'=>'Agente de Transporte',
    'FromName'=>'De Nome',
    'FromEmail'=>'De Email',
    'ReplyToName'=>'Responder ao Nome',
    'ReplyToEmail'=>'Responder ao Email',
    'Host'=>'Anfitrião',
    'SMTPAuthentication'=>'Autenticação SMTP',
    'Encryption'=>'Encryption',
    'Port'=>'Porta',
    'Next'=>'Próximo',
    'NewAssessmentQuestion'=>'Nova Avaliação Pergunta',
    'Question'=>'Pergunta',
    'RiskScore'=>'Escore de Risco',
    'SubmitRisk'=>'Submeta Risco',
    'Answer'=>'Responda',
    'AddQuestion'=>'Adicionar Pergunta',
    'SaveAssessment'=>'Salvar Avaliação',
    'SendAssessment'=>'Enviar Avaliação',
    'DeleteAssessment'=>'Excluir Avaliação',
    'AssessmentName'=>'Nome Avaliação',
    'SendTo'=>'Enviar Para',
    'ActiveAssessments'=>'Avaliações Ativos',
    'SentTo'=>'Enviado Para',
    'From'=>'A Partir De',
    'Key'=>'Chave',
    ''=>'',
);

?>
