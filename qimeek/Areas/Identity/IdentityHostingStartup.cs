using System;
using System.IO;
using System.Xml.Serialization;
using Microsoft.AspNetCore.Hosting;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Identity.UI;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.DependencyInjection;
using qimeek.Data;

[assembly: HostingStartup(typeof(qimeek.Areas.Identity.IdentityHostingStartup))]
namespace qimeek.Areas.Identity
{
    public class IdentityHostingStartup : IHostingStartup
    {
        public void Configure(IWebHostBuilder builder)
        {
            // read external config
            var mySerializer = new XmlSerializer(typeof(QimeekConfig));
            #if DEBUG
            using var myFileStream = new FileStream(@"..\..\qimeek_prod.xml", FileMode.Open);
            #elif RELEASE
            using var myFileStream = new FileStream("qimeek_prod.xml", FileMode.Open);
            #endif
            var qimeekConfig = (QimeekConfig)mySerializer.Deserialize(myFileStream);

            builder.ConfigureServices((context, services) => {
                services.AddDbContext<QimeekDbContext>(options =>
                    options.UseNpgsql(qimeekConfig.DbConnectionString));

                services.AddDefaultIdentity<IdentityUser>(options => options.SignIn.RequireConfirmedAccount = true)
                    .AddEntityFrameworkStores<QimeekDbContext>();
            });
        }
    }
}