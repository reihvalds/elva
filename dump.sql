USE [master]
GO
/****** Object:  Database [elva]    Script Date: 07.05.2025 22:04:19 ******/
CREATE DATABASE [elva]
 CONTAINMENT = NONE
 ON  PRIMARY
( NAME = N'elva', FILENAME = N'/var/opt/mssql/data/elva.mdf' , SIZE = 8192KB , MAXSIZE = UNLIMITED, FILEGROWTH = 65536KB )
 LOG ON
( NAME = N'elva_log', FILENAME = N'/var/opt/mssql/data/elva_log.ldf' , SIZE = 8192KB , MAXSIZE = 2048GB , FILEGROWTH = 65536KB )
 WITH CATALOG_COLLATION = DATABASE_DEFAULT, LEDGER = OFF
GO
ALTER DATABASE [elva] SET COMPATIBILITY_LEVEL = 160
GO
IF (1 = FULLTEXTSERVICEPROPERTY('IsFullTextInstalled'))
begin
EXEC [elva].[dbo].[sp_fulltext_database] @action = 'enable'
end
GO
ALTER DATABASE [elva] SET ANSI_NULL_DEFAULT OFF
GO
ALTER DATABASE [elva] SET ANSI_NULLS OFF
GO
ALTER DATABASE [elva] SET ANSI_PADDING OFF
GO
ALTER DATABASE [elva] SET ANSI_WARNINGS OFF
GO
ALTER DATABASE [elva] SET ARITHABORT OFF
GO
ALTER DATABASE [elva] SET AUTO_CLOSE OFF
GO
ALTER DATABASE [elva] SET AUTO_SHRINK OFF
GO
ALTER DATABASE [elva] SET AUTO_UPDATE_STATISTICS ON
GO
ALTER DATABASE [elva] SET CURSOR_CLOSE_ON_COMMIT OFF
GO
ALTER DATABASE [elva] SET CURSOR_DEFAULT  GLOBAL
GO
ALTER DATABASE [elva] SET CONCAT_NULL_YIELDS_NULL OFF
GO
ALTER DATABASE [elva] SET NUMERIC_ROUNDABORT OFF
GO
ALTER DATABASE [elva] SET QUOTED_IDENTIFIER OFF
GO
ALTER DATABASE [elva] SET RECURSIVE_TRIGGERS OFF
GO
ALTER DATABASE [elva] SET  ENABLE_BROKER
GO
ALTER DATABASE [elva] SET AUTO_UPDATE_STATISTICS_ASYNC OFF
GO
ALTER DATABASE [elva] SET DATE_CORRELATION_OPTIMIZATION OFF
GO
ALTER DATABASE [elva] SET TRUSTWORTHY OFF
GO
ALTER DATABASE [elva] SET ALLOW_SNAPSHOT_ISOLATION OFF
GO
ALTER DATABASE [elva] SET PARAMETERIZATION SIMPLE
GO
ALTER DATABASE [elva] SET READ_COMMITTED_SNAPSHOT OFF
GO
ALTER DATABASE [elva] SET HONOR_BROKER_PRIORITY OFF
GO
ALTER DATABASE [elva] SET RECOVERY FULL
GO
ALTER DATABASE [elva] SET  MULTI_USER
GO
ALTER DATABASE [elva] SET PAGE_VERIFY CHECKSUM
GO
ALTER DATABASE [elva] SET DB_CHAINING OFF
GO
ALTER DATABASE [elva] SET FILESTREAM( NON_TRANSACTED_ACCESS = OFF )
GO
ALTER DATABASE [elva] SET TARGET_RECOVERY_TIME = 60 SECONDS
GO
ALTER DATABASE [elva] SET DELAYED_DURABILITY = DISABLED
GO
ALTER DATABASE [elva] SET ACCELERATED_DATABASE_RECOVERY = OFF
GO
EXEC sys.sp_db_vardecimal_storage_format N'elva', N'ON'
GO
ALTER DATABASE [elva] SET QUERY_STORE = ON
GO
ALTER DATABASE [elva] SET QUERY_STORE (OPERATION_MODE = READ_WRITE, CLEANUP_POLICY = (STALE_QUERY_THRESHOLD_DAYS = 30), DATA_FLUSH_INTERVAL_SECONDS = 900, INTERVAL_LENGTH_MINUTES = 60, MAX_STORAGE_SIZE_MB = 1000, QUERY_CAPTURE_MODE = AUTO, SIZE_BASED_CLEANUP_MODE = AUTO, MAX_PLANS_PER_QUERY = 200, WAIT_STATS_CAPTURE_MODE = ON)
GO
USE [elva]
GO
/****** Object:  Table [dbo].[auth_assignment]    Script Date: 07.05.2025 22:04:19 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[auth_assignment](
    [item_name] [nvarchar](64) NOT NULL,
    [user_id] [nvarchar](64) NOT NULL,
    [created_at] [int] NULL,
    PRIMARY KEY CLUSTERED
(
    [item_name] ASC,
[user_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
    ) ON [PRIMARY]
    GO
/****** Object:  Table [dbo].[auth_item]    Script Date: 07.05.2025 22:04:19 ******/
    SET ANSI_NULLS ON
    GO
    SET QUOTED_IDENTIFIER ON
    GO
CREATE TABLE [dbo].[auth_item](
    [name] [nvarchar](64) NOT NULL,
    [type] [smallint] NOT NULL,
    [description] [nvarchar](max) NULL,
    [rule_name] [nvarchar](64) NULL,
    [data] [varbinary](max) NULL,
    [created_at] [int] NULL,
    [updated_at] [int] NULL,
    PRIMARY KEY CLUSTERED
(
[name] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
    ) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
    GO
/****** Object:  Table [dbo].[auth_item_child]    Script Date: 07.05.2025 22:04:19 ******/
    SET ANSI_NULLS ON
    GO
    SET QUOTED_IDENTIFIER ON
    GO
CREATE TABLE [dbo].[auth_item_child](
    [parent] [nvarchar](64) NOT NULL,
    [child] [nvarchar](64) NOT NULL,
    PRIMARY KEY CLUSTERED
(
    [parent] ASC,
[child] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
    ) ON [PRIMARY]
    GO
/****** Object:  Table [dbo].[auth_rule]    Script Date: 07.05.2025 22:04:19 ******/
    SET ANSI_NULLS ON
    GO
    SET QUOTED_IDENTIFIER ON
    GO
CREATE TABLE [dbo].[auth_rule](
    [name] [nvarchar](64) NOT NULL,
    [data] [varbinary](max) NULL,
    [created_at] [int] NULL,
    [updated_at] [int] NULL,
    PRIMARY KEY CLUSTERED
(
[name] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
    ) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
    GO
/****** Object:  Table [dbo].[construction_sites]    Script Date: 07.05.2025 22:04:19 ******/
    SET ANSI_NULLS ON
    GO
    SET QUOTED_IDENTIFIER ON
    GO
CREATE TABLE [dbo].[construction_sites](
    [id] [int] IDENTITY(1,1) NOT NULL,
    [location] [nvarchar](255) NOT NULL,
    [quadrature] [decimal](10, 2) NOT NULL,
    [access_level] [int] NOT NULL,
    [manager_id] [int] NULL,
    [created_at] [int] NOT NULL,
    [updated_at] [int] NOT NULL,
    PRIMARY KEY CLUSTERED
(
[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
    ) ON [PRIMARY]
    GO
/****** Object:  Table [dbo].[employees]    Script Date: 07.05.2025 22:04:19 ******/
    SET ANSI_NULLS ON
    GO
    SET QUOTED_IDENTIFIER ON
    GO
CREATE TABLE [dbo].[employees](
    [id] [int] IDENTITY(1,1) NOT NULL,
    [name] [nvarchar](255) NOT NULL,
    [surname] [nvarchar](255) NOT NULL,
    [birthday] [date] NOT NULL,
    [access_level] [int] NOT NULL,
    [role] [nvarchar](255) NOT NULL,
    [manager_id] [int] NULL,
    [created_at] [int] NOT NULL,
    [updated_at] [int] NOT NULL,
    [password] [nvarchar](255) NOT NULL,
    PRIMARY KEY CLUSTERED
(
[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
    ) ON [PRIMARY]
    GO
/****** Object:  Table [dbo].[migration]    Script Date: 07.05.2025 22:04:19 ******/
    SET ANSI_NULLS ON
    GO
    SET QUOTED_IDENTIFIER ON
    GO
CREATE TABLE [dbo].[migration](
    [version] [varchar](180) NOT NULL,
    [apply_time] [int] NULL,
    PRIMARY KEY CLUSTERED
(
[version] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
    ) ON [PRIMARY]
    GO
/****** Object:  Table [dbo].[tasks]    Script Date: 07.05.2025 22:04:19 ******/
    SET ANSI_NULLS ON
    GO
    SET QUOTED_IDENTIFIER ON
    GO
CREATE TABLE [dbo].[tasks](
    [id] [int] IDENTITY(1,1) NOT NULL,
    [task] [nvarchar](255) NOT NULL,
    [employee_id] [int] NULL,
    [construction_site_id] [int] NULL,
    [date] [date] NOT NULL,
    [created_at] [int] NOT NULL,
    [updated_at] [int] NOT NULL,
    PRIMARY KEY CLUSTERED
(
[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
    ) ON [PRIMARY]
    GO
    INSERT [dbo].[auth_assignment] ([item_name], [user_id], [created_at]) VALUES (N'admin', N'1', 1746644486)
    INSERT [dbo].[auth_assignment] ([item_name], [user_id], [created_at]) VALUES (N'employee', N'3', 1746644487)
    INSERT [dbo].[auth_assignment] ([item_name], [user_id], [created_at]) VALUES (N'manager', N'2', 1746644487)
    GO
    INSERT [dbo].[auth_item] ([name], [type], [description], [rule_name], [data], [created_at], [updated_at]) VALUES (N'admin', 1, NULL, NULL, 0x733A323A224E3B223B, 1746644486, 1746644486)
    INSERT [dbo].[auth_item] ([name], [type], [description], [rule_name], [data], [created_at], [updated_at]) VALUES (N'employee', 1, NULL, NULL, 0x733A323A224E3B223B, 1746644486, 1746644486)
    INSERT [dbo].[auth_item] ([name], [type], [description], [rule_name], [data], [created_at], [updated_at]) VALUES (N'manageConstructionSites', 2, N'Manage construction sites', NULL, 0x733A323A224E3B223B, 1746644486, 1746644486)
    INSERT [dbo].[auth_item] ([name], [type], [description], [rule_name], [data], [created_at], [updated_at]) VALUES (N'manageEmployees', 2, N'Manage employees', NULL, 0x733A323A224E3B223B, 1746644486, 1746644486)
    INSERT [dbo].[auth_item] ([name], [type], [description], [rule_name], [data], [created_at], [updated_at]) VALUES (N'manageOwnTasks', 2, N'Manage own tasks', N'canManageTask', 0x733A323A224E3B223B, 1746644486, 1746644486)
    INSERT [dbo].[auth_item] ([name], [type], [description], [rule_name], [data], [created_at], [updated_at]) VALUES (N'manager', 1, NULL, NULL, 0x733A323A224E3B223B, 1746644486, 1746644486)
    INSERT [dbo].[auth_item] ([name], [type], [description], [rule_name], [data], [created_at], [updated_at]) VALUES (N'manageTasks', 2, N'Manage tasks', NULL, 0x733A323A224E3B223B, 1746644486, 1746644486)
    INSERT [dbo].[auth_item] ([name], [type], [description], [rule_name], [data], [created_at], [updated_at]) VALUES (N'viewConstructionSites', 2, N'View construction sites', NULL, 0x733A323A224E3B223B, 1746644486, 1746644486)
    INSERT [dbo].[auth_item] ([name], [type], [description], [rule_name], [data], [created_at], [updated_at]) VALUES (N'viewEmployees', 2, N'View employees', NULL, 0x733A323A224E3B223B, 1746644486, 1746644486)
    INSERT [dbo].[auth_item] ([name], [type], [description], [rule_name], [data], [created_at], [updated_at]) VALUES (N'viewOwnConstructionSites', 2, N'View own construction sites', N'canViewOwnConstructionSite', 0x733A323A224E3B223B, 1746644486, 1746644486)
    INSERT [dbo].[auth_item] ([name], [type], [description], [rule_name], [data], [created_at], [updated_at]) VALUES (N'viewOwnEmployees', 2, N'View own employees', N'canViewEmployee', 0x733A323A224E3B223B, 1746644486, 1746644486)
    INSERT [dbo].[auth_item] ([name], [type], [description], [rule_name], [data], [created_at], [updated_at]) VALUES (N'viewOwnTasks', 2, N'View own tasks', N'canViewTask', 0x733A323A224E3B223B, 1746644486, 1746644486)
    INSERT [dbo].[auth_item] ([name], [type], [description], [rule_name], [data], [created_at], [updated_at]) VALUES (N'viewTasks', 2, N'View tasks', NULL, 0x733A323A224E3B223B, 1746644486, 1746644486)
    GO
    INSERT [dbo].[auth_item_child] ([parent], [child]) VALUES (N'admin', N'manageConstructionSites')
    INSERT [dbo].[auth_item_child] ([parent], [child]) VALUES (N'admin', N'manageEmployees')
    INSERT [dbo].[auth_item_child] ([parent], [child]) VALUES (N'admin', N'manager')
    INSERT [dbo].[auth_item_child] ([parent], [child]) VALUES (N'employee', N'viewConstructionSites')
    INSERT [dbo].[auth_item_child] ([parent], [child]) VALUES (N'employee', N'viewEmployees')
    INSERT [dbo].[auth_item_child] ([parent], [child]) VALUES (N'employee', N'viewOwnConstructionSites')
    INSERT [dbo].[auth_item_child] ([parent], [child]) VALUES (N'employee', N'viewOwnEmployees')
    INSERT [dbo].[auth_item_child] ([parent], [child]) VALUES (N'employee', N'viewOwnTasks')
    INSERT [dbo].[auth_item_child] ([parent], [child]) VALUES (N'employee', N'viewTasks')
    INSERT [dbo].[auth_item_child] ([parent], [child]) VALUES (N'manageOwnTasks', N'manageTasks')
    INSERT [dbo].[auth_item_child] ([parent], [child]) VALUES (N'manager', N'employee')
    INSERT [dbo].[auth_item_child] ([parent], [child]) VALUES (N'manager', N'manageOwnTasks')
    INSERT [dbo].[auth_item_child] ([parent], [child]) VALUES (N'manager', N'manageTasks')
    INSERT [dbo].[auth_item_child] ([parent], [child]) VALUES (N'viewOwnConstructionSites', N'viewConstructionSites')
    INSERT [dbo].[auth_item_child] ([parent], [child]) VALUES (N'viewOwnEmployees', N'viewEmployees')
    INSERT [dbo].[auth_item_child] ([parent], [child]) VALUES (N'viewOwnTasks', N'viewTasks')
    GO
    INSERT [dbo].[auth_rule] ([name], [data], [created_at], [updated_at]) VALUES (N'canManageTask', 0x4F3A32333A226170705C726261635C4D616E6167655461736B52756C65223A333A7B733A343A226E616D65223B733A31333A2263616E4D616E6167655461736B223B733A393A22637265617465644174223B693A313734363634343438363B733A393A22757064617465644174223B693A313734363634343438363B7D, 1746644486, 1746644486)
    INSERT [dbo].[auth_rule] ([name], [data], [created_at], [updated_at]) VALUES (N'canViewEmployee', 0x4F3A32353A226170705C726261635C56696577456D706C6F79656552756C65223A333A7B733A343A226E616D65223B733A31353A2263616E56696577456D706C6F796565223B733A393A22637265617465644174223B693A313734363634343438363B733A393A22757064617465644174223B693A313734363634343438363B7D, 1746644486, 1746644486)
    INSERT [dbo].[auth_rule] ([name], [data], [created_at], [updated_at]) VALUES (N'canViewOwnConstructionSite', 0x4F3A33333A226170705C726261635C56696577436F6E737472756374696F6E5369746552756C65223A333A7B733A343A226E616D65223B733A32363A2263616E566965774F776E436F6E737472756374696F6E53697465223B733A393A22637265617465644174223B693A313734363634343438363B733A393A22757064617465644174223B693A313734363634343438363B7D, 1746644486, 1746644486)
    INSERT [dbo].[auth_rule] ([name], [data], [created_at], [updated_at]) VALUES (N'canViewTask', 0x4F3A32313A226170705C726261635C566965775461736B52756C65223A333A7B733A343A226E616D65223B733A31313A2263616E566965775461736B223B733A393A22637265617465644174223B693A313734363634343438363B733A393A22757064617465644174223B693A313734363634343438363B7D, 1746644486, 1746644486)
    GO
    SET IDENTITY_INSERT [dbo].[construction_sites] ON

    INSERT [dbo].[construction_sites] ([id], [location], [quadrature], [access_level], [manager_id], [created_at], [updated_at]) VALUES (1, N'Construction Site 1', CAST(812.00 AS Decimal(10, 2)), 1, 2, 1746644487, 1746644487)
    INSERT [dbo].[construction_sites] ([id], [location], [quadrature], [access_level], [manager_id], [created_at], [updated_at]) VALUES (2, N'Construction Site 2', CAST(952.00 AS Decimal(10, 2)), 2, 2, 1746644487, 1746644487)
    INSERT [dbo].[construction_sites] ([id], [location], [quadrature], [access_level], [manager_id], [created_at], [updated_at]) VALUES (3, N'Construction Site 3', CAST(695.00 AS Decimal(10, 2)), 2, 2, 1746644487, 1746644487)
    SET IDENTITY_INSERT [dbo].[construction_sites] OFF
    GO
    SET IDENTITY_INSERT [dbo].[employees] ON

    INSERT [dbo].[employees] ([id], [name], [surname], [birthday], [access_level], [role], [manager_id], [created_at], [updated_at], [password]) VALUES (1, N'admin', N'Admin', CAST(N'1995-05-07' AS Date), 1, N'admin', NULL, 1746644486, 1746644486, N'$2y$13$FTlvEsdbRpTkEPWjLVk0WOH/vP/XYiNRwmHnLUqL3V9ImTOH.I6ae')
    INSERT [dbo].[employees] ([id], [name], [surname], [birthday], [access_level], [role], [manager_id], [created_at], [updated_at], [password]) VALUES (2, N'manager', N'Manager', CAST(N'1990-05-07' AS Date), 1, N'manager', NULL, 1746644487, 1746644487, N'$2y$13$GgW5/MHpH/mRgz.a4IJt5OonimSLrHJs7EPOjvJa1BFBe8teoEmzG')
    INSERT [dbo].[employees] ([id], [name], [surname], [birthday], [access_level], [role], [manager_id], [created_at], [updated_at], [password]) VALUES (3, N'employee', N'Worker', CAST(N'2000-05-07' AS Date), 1, N'employee', 2, 1746644487, 1746644487, N'$2y$13$jphEPBtkJTTZxy4oE6LwCODUkTQ6Rjn1lDO8Fy8u5g0UwEpCk9/cu')
    SET IDENTITY_INSERT [dbo].[employees] OFF
    GO
    INSERT [dbo].[migration] ([version], [apply_time]) VALUES (N'm000000_000000_base', 1746644482)
    INSERT [dbo].[migration] ([version], [apply_time]) VALUES (N'm140506_102106_rbac_init', 1746644484)
    INSERT [dbo].[migration] ([version], [apply_time]) VALUES (N'm170907_052038_rbac_add_index_on_auth_assignment_user_id', 1746644484)
    INSERT [dbo].[migration] ([version], [apply_time]) VALUES (N'm180523_151638_rbac_updates_indexes_without_prefix', 1746644484)
    INSERT [dbo].[migration] ([version], [apply_time]) VALUES (N'm200409_110543_rbac_update_mssql_trigger', 1746644484)
    INSERT [dbo].[migration] ([version], [apply_time]) VALUES (N'm250505_000001_init_rbac', 1746644486)
    INSERT [dbo].[migration] ([version], [apply_time]) VALUES (N'm250505_000002_create_employee_table', 1746644486)
    INSERT [dbo].[migration] ([version], [apply_time]) VALUES (N'm250505_000003_create_construction_sites_table', 1746644486)
    INSERT [dbo].[migration] ([version], [apply_time]) VALUES (N'm250505_000004_create_tasks_table', 1746644486)
    GO
    SET IDENTITY_INSERT [dbo].[tasks] ON

    INSERT [dbo].[tasks] ([id], [task], [employee_id], [construction_site_id], [date], [created_at], [updated_at]) VALUES (1, N'Task for employee at Construction Site 1', 3, 1, CAST(N'2025-05-07' AS Date), 1746644487, 1746644487)
    INSERT [dbo].[tasks] ([id], [task], [employee_id], [construction_site_id], [date], [created_at], [updated_at]) VALUES (2, N'Task for employee at Construction Site 2', 3, 2, CAST(N'2025-05-07' AS Date), 1746644487, 1746644487)
    INSERT [dbo].[tasks] ([id], [task], [employee_id], [construction_site_id], [date], [created_at], [updated_at]) VALUES (3, N'Task for employee at Construction Site 3', 3, 3, CAST(N'2025-05-07' AS Date), 1746644487, 1746644487)
    SET IDENTITY_INSERT [dbo].[tasks] OFF
    GO
    SET ANSI_PADDING ON
    GO
/****** Object:  Index [idx-auth_assignment-user_id]    Script Date: 07.05.2025 22:04:19 ******/
CREATE NONCLUSTERED INDEX [idx-auth_assignment-user_id] ON [dbo].[auth_assignment]
(
	[user_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
/****** Object:  Index [idx-auth_item-type]    Script Date: 07.05.2025 22:04:19 ******/
CREATE NONCLUSTERED INDEX [idx-auth_item-type] ON [dbo].[auth_item]
(
	[type] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
ALTER TABLE [dbo].[construction_sites] ADD  DEFAULT ((1)) FOR [access_level]
    GO
ALTER TABLE [dbo].[construction_sites] ADD  DEFAULT (NULL) FOR [manager_id]
    GO
ALTER TABLE [dbo].[employees] ADD  DEFAULT ((1)) FOR [access_level]
    GO
ALTER TABLE [dbo].[employees] ADD  DEFAULT (NULL) FOR [manager_id]
    GO
ALTER TABLE [dbo].[tasks] ADD  DEFAULT (NULL) FOR [employee_id]
    GO
ALTER TABLE [dbo].[tasks] ADD  DEFAULT (NULL) FOR [construction_site_id]
    GO
ALTER TABLE [dbo].[auth_assignment]  WITH CHECK ADD FOREIGN KEY([item_name])
    REFERENCES [dbo].[auth_item] ([name])
    GO
ALTER TABLE [dbo].[auth_item]  WITH CHECK ADD FOREIGN KEY([rule_name])
    REFERENCES [dbo].[auth_rule] ([name])
    GO
ALTER TABLE [dbo].[auth_item_child]  WITH CHECK ADD FOREIGN KEY([child])
    REFERENCES [dbo].[auth_item] ([name])
    GO
ALTER TABLE [dbo].[auth_item_child]  WITH CHECK ADD FOREIGN KEY([parent])
    REFERENCES [dbo].[auth_item] ([name])
    GO
    USE [master]
    GO
ALTER DATABASE [elva] SET  READ_WRITE
GO
