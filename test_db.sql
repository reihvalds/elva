USE [master]
GO
/****** Object:  Database [elva_test]    Script Date: 07.05.2025 23:09:41 ******/
CREATE DATABASE [elva_test]
 CONTAINMENT = NONE
 ON  PRIMARY
( NAME = N'elva_test', FILENAME = N'/var/opt/mssql/data/elva_test.mdf' , SIZE = 8192KB , MAXSIZE = UNLIMITED, FILEGROWTH = 65536KB )
 LOG ON
( NAME = N'elva_test_log', FILENAME = N'/var/opt/mssql/data/elva_test_log.ldf' , SIZE = 8192KB , MAXSIZE = 2048GB , FILEGROWTH = 65536KB )
 WITH CATALOG_COLLATION = DATABASE_DEFAULT, LEDGER = OFF
GO
ALTER DATABASE [elva_test] SET COMPATIBILITY_LEVEL = 160
GO
IF (1 = FULLTEXTSERVICEPROPERTY('IsFullTextInstalled'))
begin
EXEC [elva_test].[dbo].[sp_fulltext_database] @action = 'enable'
end
GO
ALTER DATABASE [elva_test] SET ANSI_NULL_DEFAULT OFF
GO
ALTER DATABASE [elva_test] SET ANSI_NULLS OFF
GO
ALTER DATABASE [elva_test] SET ANSI_PADDING OFF
GO
ALTER DATABASE [elva_test] SET ANSI_WARNINGS OFF
GO
ALTER DATABASE [elva_test] SET ARITHABORT OFF
GO
ALTER DATABASE [elva_test] SET AUTO_CLOSE OFF
GO
ALTER DATABASE [elva_test] SET AUTO_SHRINK OFF
GO
ALTER DATABASE [elva_test] SET AUTO_UPDATE_STATISTICS ON
GO
ALTER DATABASE [elva_test] SET CURSOR_CLOSE_ON_COMMIT OFF
GO
ALTER DATABASE [elva_test] SET CURSOR_DEFAULT  GLOBAL
GO
ALTER DATABASE [elva_test] SET CONCAT_NULL_YIELDS_NULL OFF
GO
ALTER DATABASE [elva_test] SET NUMERIC_ROUNDABORT OFF
GO
ALTER DATABASE [elva_test] SET QUOTED_IDENTIFIER OFF
GO
ALTER DATABASE [elva_test] SET RECURSIVE_TRIGGERS OFF
GO
ALTER DATABASE [elva_test] SET  ENABLE_BROKER
GO
ALTER DATABASE [elva_test] SET AUTO_UPDATE_STATISTICS_ASYNC OFF
GO
ALTER DATABASE [elva_test] SET DATE_CORRELATION_OPTIMIZATION OFF
GO
ALTER DATABASE [elva_test] SET TRUSTWORTHY OFF
GO
ALTER DATABASE [elva_test] SET ALLOW_SNAPSHOT_ISOLATION OFF
GO
ALTER DATABASE [elva_test] SET PARAMETERIZATION SIMPLE
GO
ALTER DATABASE [elva_test] SET READ_COMMITTED_SNAPSHOT OFF
GO
ALTER DATABASE [elva_test] SET HONOR_BROKER_PRIORITY OFF
GO
ALTER DATABASE [elva_test] SET RECOVERY FULL
GO
ALTER DATABASE [elva_test] SET  MULTI_USER
GO
ALTER DATABASE [elva_test] SET PAGE_VERIFY CHECKSUM
GO
ALTER DATABASE [elva_test] SET DB_CHAINING OFF
GO
ALTER DATABASE [elva_test] SET FILESTREAM( NON_TRANSACTED_ACCESS = OFF )
GO
ALTER DATABASE [elva_test] SET TARGET_RECOVERY_TIME = 60 SECONDS
GO
ALTER DATABASE [elva_test] SET DELAYED_DURABILITY = DISABLED
GO
ALTER DATABASE [elva_test] SET ACCELERATED_DATABASE_RECOVERY = OFF
GO
EXEC sys.sp_db_vardecimal_storage_format N'elva_test', N'ON'
GO
ALTER DATABASE [elva_test] SET QUERY_STORE = ON
GO
ALTER DATABASE [elva_test] SET QUERY_STORE (OPERATION_MODE = READ_WRITE, CLEANUP_POLICY = (STALE_QUERY_THRESHOLD_DAYS = 30), DATA_FLUSH_INTERVAL_SECONDS = 900, INTERVAL_LENGTH_MINUTES = 60, MAX_STORAGE_SIZE_MB = 1000, QUERY_CAPTURE_MODE = AUTO, SIZE_BASED_CLEANUP_MODE = AUTO, MAX_PLANS_PER_QUERY = 200, WAIT_STATS_CAPTURE_MODE = ON)
GO
USE [elva_test]
GO
/****** Object:  Table [dbo].[auth_assignment]    Script Date: 07.05.2025 23:09:42 ******/
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
/****** Object:  Table [dbo].[auth_item]    Script Date: 07.05.2025 23:09:42 ******/
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
/****** Object:  Table [dbo].[auth_item_child]    Script Date: 07.05.2025 23:09:42 ******/
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
/****** Object:  Table [dbo].[auth_rule]    Script Date: 07.05.2025 23:09:42 ******/
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
/****** Object:  Table [dbo].[construction_sites]    Script Date: 07.05.2025 23:09:42 ******/
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
/****** Object:  Table [dbo].[employees]    Script Date: 07.05.2025 23:09:42 ******/
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
/****** Object:  Table [dbo].[migration]    Script Date: 07.05.2025 23:09:42 ******/
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
/****** Object:  Table [dbo].[tasks]    Script Date: 07.05.2025 23:09:42 ******/
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
    SET ANSI_PADDING ON
    GO
/****** Object:  Index [idx-auth_assignment-user_id]    Script Date: 07.05.2025 23:09:42 ******/
CREATE NONCLUSTERED INDEX [idx-auth_assignment-user_id] ON [dbo].[auth_assignment]
(
	[user_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
/****** Object:  Index [idx-auth_item-type]    Script Date: 07.05.2025 23:09:42 ******/
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
ALTER DATABASE [elva_test] SET  READ_WRITE
GO
